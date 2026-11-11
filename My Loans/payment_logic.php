<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login_&_Register/index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";
$error = "";

if (isset($_GET['id'])) {
    $loan_id = $_GET['id'];
} elseif (isset($_POST['loan_id'])) {
    $loan_id = $_POST['loan_id'];
} else {
    
    die("Invalid Loan ID");
}


$sql = "SELECT l.*, lr.title, u.full_name as lender_name 
        FROM loans l 
        JOIN loan_requests lr ON l.loan_request_id = lr.id 
        JOIN users u ON l.lender_id = u.id 
        WHERE l.id = ? AND l.borrower_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $loan_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Loan not found or access denied.");
}

$loan = $result->fetch_assoc();


$total_due = $loan['monthly_payment'] * $loan['duration_months'];


$paid_sql = "SELECT SUM(amount) as total_paid FROM repayments WHERE loan_id = ? AND status = 'paid'";
$paid_stmt = $conn->prepare($paid_sql);
$paid_stmt->bind_param("i", $loan_id);
$paid_stmt->execute();
$paid_res = $paid_stmt->get_result();
$paid_row = $paid_res->fetch_assoc();
$total_paid = $paid_row['total_paid'] ?? 0;

$remaining = $total_due - $total_paid;
$percent = ($total_due > 0) ? round(($total_paid / $total_due) * 100) : 0;


$rep_sql = "SELECT * FROM repayments WHERE loan_id = ? AND status = 'pending' ORDER BY due_date ASC LIMIT 1";
$rep_stmt = $conn->prepare($rep_sql);
$rep_stmt->bind_param("i", $loan_id);
$rep_stmt->execute();
$rep_res = $rep_stmt->get_result();
$repayment = $rep_res->fetch_assoc();


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pay_installment'])) {
    if (!$repayment) {
        $error = "No pending installments.";
    } else {
        $amount = $repayment['amount'];
        $lender_id = $loan['lender_id'];

       
        $bal_sql = "SELECT balance FROM users WHERE id = ?";
        $bal_stmt = $conn->prepare($bal_sql);
        $bal_stmt->bind_param("i", $user_id);
        $bal_stmt->execute();
        $bal_res = $bal_stmt->get_result();
        $user_bal = $bal_res->fetch_assoc()['balance'];

        if ($user_bal >= $amount) {
            $conn->begin_transaction();
            try {
                
                $conn->query("UPDATE users SET balance = balance - $amount WHERE id = $user_id");

                
                $conn->query("UPDATE users SET balance = balance + $amount WHERE id = $lender_id");

                
                $conn->query("INSERT INTO transactions (user_id, type, amount, description) VALUES ($user_id, 'loan_repayment', $amount, 'Repayment for Loan #$loan_id')");
                $conn->query("INSERT INTO transactions (user_id, type, amount, description) VALUES ($lender_id, 'investment_return', $amount, 'Repayment Received for Loan #$loan_id')");

                
                $repayment_id = $repayment['id'];
                $conn->query("UPDATE repayments SET status = 'paid', payment_date = NOW() WHERE id = $repayment_id");

                
                $check_pending = $conn->query("SELECT COUNT(*) as count FROM repayments WHERE loan_id = $loan_id AND status = 'pending'");
                if ($check_pending->fetch_assoc()['count'] == 0) {
                    $conn->query("UPDATE loans SET status = 'completed' WHERE id = $loan_id");
                    $conn->query("UPDATE loan_requests SET status = 'repaid' WHERE id = " . $loan['loan_request_id']);
                }

                $conn->commit();
                $message = "Payment successful!";
                
                
                $total_paid += $amount;
                $remaining -= $amount;
                $percent = ($total_due > 0) ? round(($total_paid / $total_due) * 100) : 0;
                $repayment = null;
                
                
                $rep_stmt->execute();
                $rep_res = $rep_stmt->get_result();
                $repayment = $rep_res->fetch_assoc();

            } catch (Exception $e) {
                $conn->rollback();
                $error = "Payment failed: " . $e->getMessage();
            }
        } else {
            $error = "Insufficient balance in your wallet.";
        }
    }
}
?>