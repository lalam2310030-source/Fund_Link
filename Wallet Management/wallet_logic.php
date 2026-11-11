<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login_&_Register/index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deposit_amount'])) {
    $amount = $_POST['deposit_amount'];
    if ($amount > 0) {
        $conn->begin_transaction();
        try {
           
            $stmt = $conn->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
            $stmt->bind_param("di", $amount, $user_id);
            $stmt->execute();

           
            $stmt = $conn->prepare("INSERT INTO transactions (user_id, type, amount, description) VALUES (?, 'deposit', ?, 'Quick Deposit')");
            $stmt->bind_param("id", $user_id, $amount);
            $stmt->execute();

            $conn->commit();
            $message = "Deposit of ৳$amount successful!";
        } catch (Exception $e) {
            $conn->rollback();
            $message = "Error: " . $e->getMessage();
        }
    } else {
        $message = "Invalid amount.";
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['withdraw_amount'])) {
    $amount = $_POST['withdraw_amount'];
    
    
    $stmt = $conn->prepare("SELECT balance FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $current_balance = $row['balance'];

    if ($amount > 0 && $amount <= $current_balance) {
        $conn->begin_transaction();
        try {
            
            $stmt = $conn->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
            $stmt->bind_param("di", $amount, $user_id);
            $stmt->execute();

            
            $stmt = $conn->prepare("INSERT INTO transactions (user_id, type, amount, description) VALUES (?, 'withdrawal', ?, 'Fund Withdrawal')");
            $stmt->bind_param("id", $user_id, $amount);
            $stmt->execute();

            $conn->commit();
            $message = "Withdrawal of ৳$amount successful!";
        } catch (Exception $e) {
            $conn->rollback();
            $message = "Error: " . $e->getMessage();
        }
    } else {
        $message = "Invalid amount or insufficient funds.";
    }
}


$stmt = $conn->prepare("SELECT balance FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$balance = $user['balance'] ?? 0;

$transactions = [];
$stmt = $conn->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $transactions[] = $row;
}
?>