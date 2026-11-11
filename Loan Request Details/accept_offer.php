<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    
    die("Unauthorized");
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $offer_id = intval($_POST['offer_id']);
    $loan_request_id = intval($_POST['loan_request_id']);


    $check_sql = "SELECT * FROM loan_requests WHERE id = $loan_request_id AND user_id = $user_id AND status = 'open'";
    $check_res = $conn->query($check_sql);

    if ($check_res->num_rows == 0) {
        die("Invalid request or loan already processed.");
    }
    
    $request = $check_res->fetch_assoc();

  
    $offer_sql = "SELECT * FROM loan_offers WHERE id = $offer_id AND loan_request_id = $loan_request_id AND status = 'pending'";
    $offer_res = $conn->query($offer_sql);

    if ($offer_res->num_rows == 0) {
        die("Invalid offer.");
    }

    $offer = $offer_res->fetch_assoc();
    $lender_id = $offer['lender_user_id'];
    $amount = $request['amount'];

    
    $balance_sql = "SELECT balance FROM users WHERE id = $lender_id";
    $balance_res = $conn->query($balance_sql);
    $lender_balance = $balance_res->fetch_assoc()['balance'];

    if ($lender_balance < $amount) {
        die("Error: Lender does not have sufficient funds.");
    }

   
    $conn->begin_transaction();

    try {
       
        $update_lender = "UPDATE users SET balance = balance - $amount WHERE id = $lender_id";
        if (!$conn->query($update_lender)) throw new Exception("Error updating lender balance");

       
        $update_borrower = "UPDATE users SET balance = balance + $amount WHERE id = $user_id";
        if (!$conn->query($update_borrower)) throw new Exception("Error updating borrower balance");

        
        $log_lender = "INSERT INTO transactions (user_id, type, amount, description) VALUES ($lender_id, 'investment_return', -$amount, 'Loan Investment for Request #$loan_request_id')"; // Using negative amount for clarity or type should handle it. Let's use 'withdrawal' logic or specific type. The enum has 'loan_disbursement' which fits better for borrower. For lender, it's an investment.
        
        
        $conn->query("INSERT INTO transactions (user_id, type, amount, description) VALUES ($lender_id, 'withdrawal', $amount, 'Investment in Loan #$loan_request_id')");
        $conn->query("INSERT INTO transactions (user_id, type, amount, description) VALUES ($user_id, 'loan_disbursement', $amount, 'Loan Received #$loan_request_id')");


       
        $update_offer = "UPDATE loan_offers SET status = 'accepted' WHERE id = $offer_id";
        if (!$conn->query($update_offer)) throw new Exception("Error updating offer");

        
        $reject_others = "UPDATE loan_offers SET status = 'rejected' WHERE loan_request_id = $loan_request_id AND id != $offer_id";
        if (!$conn->query($reject_others)) throw new Exception("Error rejecting other offers");

     
        $update_request = "UPDATE loan_requests SET status = 'active' WHERE id = $loan_request_id";
        if (!$conn->query($update_request)) throw new Exception("Error updating request");

        
        $interest_rate = $offer['interest_rate'];
        $duration = $request['duration_months'];
        
        
        $r = $interest_rate / 100 / 12;
        if ($r > 0) {
             $monthly_payment = $amount * $r * pow(1 + $r, $duration) / (pow(1 + $r, $duration) - 1);
        } else {
             $monthly_payment = $amount / $duration;
        }

        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d', strtotime("+$duration months"));

        $insert_loan = "INSERT INTO loans (loan_request_id, borrower_id, lender_id, amount, interest_rate, duration_months, start_date, end_date, monthly_payment, status) 
                        VALUES ($loan_request_id, $user_id, $lender_id, $amount, $interest_rate, $duration, '$start_date', '$end_date', $monthly_payment, 'active')";
        if (!$conn->query($insert_loan)) throw new Exception("Error creating loan: " . $conn->error);
        
       
        $loan_id = $conn->insert_id;
        for ($i = 1; $i <= $duration; $i++) {
            $due_date = date('Y-m-d', strtotime("+$i months"));
            $insert_repayment = "INSERT INTO repayments (loan_id, amount, due_date, status) VALUES ($loan_id, $monthly_payment, '$due_date', 'pending')";
            $conn->query($insert_repayment);
        }

        $conn->commit();
        
        
        header("Location: index.php?id=$loan_request_id&msg=Offer Accepted Successfully");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        die("Error processing loan: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
    exit();
}
?>
