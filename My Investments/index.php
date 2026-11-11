<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login_&_Register/index.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$summary_sql = "SELECT 
    COUNT(*) as active_count, 
    SUM(amount) as total_invested 
    FROM loans 
    WHERE lender_id = $user_id AND status = 'active'";
$summary_res = $conn->query($summary_sql);
$summary = $summary_res->fetch_assoc();


$earnings_sql = "SELECT SUM(amount) as total_received FROM transactions WHERE user_id = $user_id AND type = 'investment_return'";
$earnings_res = $conn->query($earnings_sql);
$total_received_all = $earnings_res->fetch_assoc()['total_received'] ?? 0;



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyInvestments</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <div class="MainWrapper">
        <div class="back-nav" style="margin-bottom: 20px;">
             <a href="../index.php" style="text-decoration: none; color: #333;"><i class="fa-solid fa-arrow-left"></i> Back to Home</a>
        </div>

        <div class="PageHeader">
            <p><strong>My Investments</strong></p>
            <h5>Track your lending portfolio</h5>
        </div>

        <div class="PortfolioSummary dflex">
            <div class="SummaryCard active-projects">
                <p>Active Loans</p>
                <h2><?php echo $summary['active_count']; ?></h2>
            </div>
            <div class="SummaryCard total-invested">
                <p>Total Invested</p>
                <h2>৳<?php echo number_format($summary['total_invested'] ?? 0); ?></h2>
            </div>
            <div class="SummaryCard total-earnings">
                <p>Total Received</p>
                <h2>৳<?php echo number_format($total_received_all); ?></h2>
            </div>
        </div>

        <?php
        $loans_sql = "SELECT l.*, lr.title, u.full_name as borrower_name 
                      FROM loans l 
                      JOIN loan_requests lr ON l.loan_request_id = lr.id 
                      JOIN users u ON l.borrower_id = u.id 
                      WHERE l.lender_id = $user_id AND l.status = 'active'";
        $loans_res = $conn->query($loans_sql);

        if ($loans_res->num_rows > 0) {
            while ($loan = $loans_res->fetch_assoc()) {
                $loan_id = $loan['id'];
                $total_due = $loan['monthly_payment'] * $loan['duration_months'];
                
                
                $paid_sql = "SELECT SUM(amount) as total_paid FROM repayments WHERE loan_id = $loan_id AND status = 'paid'";
                $paid_res = $conn->query($paid_sql);
                $total_paid = $paid_res->fetch_assoc()['total_paid'] ?? 0;
                
                $percent = ($total_due > 0) ? round(($total_paid / $total_due) * 100) : 0;
        ?>
        <div class="InvestmentDetailCard">
            <div class="CardHeader dflex j-between align-center">
                <div>
                    <h3><?php echo htmlspecialchars($loan['title']); ?></h3>
                    <span>#LOAN-<?php echo $loan['id']; ?></span>
                </div>
                <div class="StatusBadge status-active">Active</div>
            </div>

            <div class="LoanDetailsRow dflex">
                <div class="DetailItem borrower-identity">
                    <p>Borrower</p>
                    <div class="dflex align-center">
                        <div class="profile-avatar"><i class="fa-solid fa-user"></i></div>
                        <strong><?php echo htmlspecialchars($loan['borrower_name']); ?></strong>
                    </div>
                </div>

                <div class="DetailItem capital-amount">
                    <p>Invested</p>
                    <h4>৳<?php echo number_format($loan['amount']); ?></h4>
                </div>

                <div class="DetailItem profit-rate">
                    <p>Rate</p>
                    <h4 class="text-success"><?php echo $loan['interest_rate']; ?>%</h4>
                </div>

                <div class="DetailItem installment-value">
                    <p>Monthly Return</p>
                    <h4>৳<?php echo number_format($loan['monthly_payment']); ?></h4>
                </div>

                <div class="DetailItem time-period">
                    <p>Duration</p>
                    <h4><?php echo $loan['duration_months']; ?> mo</h4>
                </div>
            </div>

            <div class="ProgressTrackingArea">
                <div class="ProgressLabels dflex j-between">
                    <p>Repayment Progress</p>
                    <p><?php echo $percent; ?>% Complete</p>
                </div>
                <div class="ProgressContainer">
                    <div class="ProgressBarFill bg-success" style="width: <?php echo $percent; ?>%;"></div>
                </div>
                <p class="ProgressSubtext">৳<?php echo number_format($total_paid); ?> of ৳<?php echo number_format($total_due); ?> received</p>
            </div>
        </div>
        <?php
            }
        } else {
            echo "<p>No active investments found.</p>";
        }
        ?>

    </div>
</body>
</html>