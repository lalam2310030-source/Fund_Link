<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login_&_Register/index.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$active_loans_count = 0;
$total_borrowed = 0;
$total_paid = 0;


$loans = [];
$sql = "SELECT l.*, lr.title, u.full_name as lender_name 
        FROM loans l 
        JOIN loan_requests lr ON l.loan_request_id = lr.id 
        JOIN users u ON l.lender_id = u.id 
        WHERE l.borrower_id = ? AND l.status = 'active'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $loans[] = $row;
    $active_loans_count++;
    $total_borrowed += $row['amount'];
    
}


$pending_requests = [];
$sql_pending = "SELECT lr.*, 
                (SELECT COUNT(*) FROM loan_offers WHERE loan_request_id = lr.id) as offer_count 
                FROM loan_requests lr 
                WHERE lr.user_id = ? AND lr.status = 'open' 
                ORDER BY lr.created_at DESC";
$stmt_pending = $conn->prepare($sql_pending);
$stmt_pending->bind_param("i", $user_id);
$stmt_pending->execute();
$res_pending = $stmt_pending->get_result();
while ($row = $res_pending->fetch_assoc()) {
    $pending_requests[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyLoans</title> <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="MainWrapper">
        <div class="TopNav" style="margin-bottom: 20px;">
            <a href="../Dashboard/index.php" style="text-decoration: none; color: #333;"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
        </div>
        <div class="Top">
            <p><strong>MyLoans</strong></p>
            <h5>Track your borrowed funds</h5>
        </div>

        <div class="Middle dflex">
            <div class="SummaryCard bg-purple">
                <p>Active Loans</p>
                <h2><?php echo $active_loans_count; ?></h2>
            </div>
            <div class="SummaryCard bg-orange">
                <p>Total Borrowed</p>
                <h2>৳<?php echo number_format($total_borrowed); ?></h2>
            </div>
            <div class="SummaryCard bg-green">
                <p>Total Paid</p>
                <h2>৳<?php echo number_format($total_paid); ?></h2>
            </div>
            <div class="SummaryCard bg-yellow">
                <p>Pending Requests</p>
                <h2><?php echo count($pending_requests); ?></h2>
            </div>
        </div>

        
        <?php if(count($pending_requests) > 0): ?>
            <h3 style="margin: 30px 0 15px;">Pending Loan Requests</h3>
            <?php foreach($pending_requests as $req): ?>
            <div class="LoanDetailSection" style="border-left: 5px solid #8b5cf6;">
                <div class="DetailHeader dflex">
                    <div>
                        <h3><?php echo htmlspecialchars($req['title']); ?></h3>
                        <span>Requested: <?php echo date('M d, Y', strtotime($req['created_at'])); ?></span>
                    </div>
                    <div class="StatusBadge" style="background: #e9d5ff; color: #6b21a8;"><?php echo ucfirst($req['status']); ?></div>
                </div>

                <div class="HorizontalStats dflex">
                    <div class="h-stat bg-pink-soft">
                        <p>Amount</p>
                        <h4>৳<?php echo number_format($req['amount']); ?></h4>
                    </div>
                    <div class="h-stat bg-yellow-soft">
                        <p>Max Rate</p>
                        <h4><?php echo $req['max_interest_rate']; ?>%</h4>
                    </div>
                    <div class="h-stat bg-red-soft">
                        <p>Duration</p>
                        <h4><?php echo $req['duration_months']; ?> mo</h4>
                    </div>
                    <div class="h-stat bg-blue-soft">
                        <p>Offers</p>
                        <h4><?php echo $req['offer_count']; ?></h4>
                    </div>
                </div>

                <div class="ActionButtons" style="margin-top: 20px;">
                    <a href="../Loan Request Details/index.php?id=<?php echo $req['id']; ?>" class="btn-full btn-purple-main" style="display: inline-block; width: auto; padding: 10px 20px; text-decoration: none; color: white;">View Offers</a>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <h3 style="margin: 30px 0 15px;">Active Loans</h3>
        <?php if(count($loans) > 0): ?>
            <?php foreach($loans as $loan): ?>
            <div class="LoanDetailSection">
                <div class="DetailHeader dflex">
                    <div>
                        <h3><?php echo htmlspecialchars($loan['title']); ?></h3>
                        <span>#LN-<?php echo $loan['id']; ?></span>
                    </div>
                    <div class="StatusBadge active"><?php echo ucfirst($loan['status']); ?></div>
                </div>

                <div class="HorizontalStats dflex">
                    <div class="h-stat bg-blue-soft">
                        <p>Lender</p>
                        <div class="dflex align-center">
                            <div class="small-icon bg-purple-light"><i class="fa-solid fa-user"></i></div>
                            <strong><?php echo htmlspecialchars($loan['lender_name']); ?></strong>
                        </div>
                    </div>
                    <div class="h-stat bg-pink-soft">
                        <p>Amount</p>
                        <h4>৳<?php echo number_format($loan['amount']); ?></h4>
                    </div>
                    <div class="h-stat bg-yellow-soft">
                        <p>Rate</p>
                        <h4><?php echo $loan['interest_rate']; ?>%</h4>
                    </div>
                    <div class="h-stat bg-orange-soft">
                        <p>Monthly</p>
                        <h4>৳<?php echo number_format($loan['monthly_payment']); ?></h4>
                    </div>
                    <div class="h-stat bg-red-soft">
                        <p>Duration</p>
                        <h4><?php echo $loan['duration_months']; ?> mo</h4>
                    </div>
                </div>

                <div class="ProgressArea">
                    <div class="ProgressText dflex j-between">
                        <p>Progress</p>
                        <p>0% Complete</p>
                    </div>
                    <div class="ProgressBar">
                        <div class="ProgressFill bg-green" style="width: 0%;"></div>
                    </div>
                    <p class="paid-text">৳0 paid</p>
                </div>

                <button class="btn-full btn-purple-main"> <a href="Make_Payment.php?id=<?php echo $loan['id']; ?>" style="color: white; text-decoration: none;">Make Payment</a></button>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; margin-top: 20px;">You have no active loans.</p>
        <?php endif; ?>
    </div>
</body>
</html>