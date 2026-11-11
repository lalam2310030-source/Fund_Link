<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loan_request_id = intval($_POST['loan_request_id']);
    $interest_rate = floatval($_POST['interest_rate']);
    $lender_id = $_SESSION['user_id'];

    
    if ($interest_rate <= 0 || $loan_request_id <= 0) {
        die("Invalid input");
    }

    
    $check = "SELECT id FROM loan_offers WHERE loan_request_id = $loan_request_id AND lender_user_id = $lender_id";
    if ($conn->query($check)->num_rows > 0) {
        die("You have already made an offer on this request.");
    }

    $sql = "INSERT INTO loan_offers (loan_request_id, lender_user_id, interest_rate, status) VALUES ($loan_request_id, $lender_id, $interest_rate, 'pending')";
    
    if ($conn->query($sql)) {
        header("Location: index.php?id=$loan_request_id&msg=Offer Submitted");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
