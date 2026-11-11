<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login_&_Register/index.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$sql_balance = "SELECT balance FROM users WHERE id = $user_id";
$res_balance = $conn->query($sql_balance);
$balance = ($res_balance->num_rows > 0) ? $res_balance->fetch_assoc()['balance'] : 0;


$sql_borrowed = "SELECT SUM(amount) as total_borrowed FROM loans WHERE borrower_id = $user_id AND status = 'active'";
$res_borrowed = $conn->query($sql_borrowed);
$total_borrowed = $res_borrowed->fetch_assoc()['total_borrowed'] ?? 0;


$sql_invested = "SELECT SUM(amount) as total_invested FROM loans WHERE lender_id = $user_id AND status = 'active'";
$res_invested = $conn->query($sql_invested);
$total_invested = $res_invested->fetch_assoc()['total_invested'] ?? 0;


$sql_earnings = "SELECT SUM(amount) as total_earnings FROM transactions WHERE user_id = $user_id AND type = 'investment_return'";
$res_earnings = $conn->query($sql_earnings);
$total_earnings = $res_earnings->fetch_assoc()['total_earnings'] ?? 0;


$sql_rate = "SELECT AVG(interest_rate) as avg_rate FROM loans WHERE borrower_id = $user_id AND status = 'active'";
$res_rate = $conn->query($sql_rate);
$avg_rate = $res_rate->fetch_assoc()['avg_rate'] ?? 0;


$transactions = [];
$sql_trans = "SELECT * FROM transactions WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 5";
$res_trans = $conn->query($sql_trans);
while ($row = $res_trans->fetch_assoc()) {
    $transactions[] = $row;
}


$market_requests = [];
$sql_market = "SELECT lr.*, u.full_name 
               FROM loan_requests lr 
               JOIN users u ON lr.user_id = u.id 
               WHERE lr.status = 'open' AND lr.user_id != $user_id 
               ORDER BY lr.created_at DESC LIMIT 3";
$res_market = $conn->query($sql_market);
while ($row = $res_market->fetch_assoc()) {
    $market_requests[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FundLink | Full Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="MainContainer">
        <header class="DashboardHeader">
            <div class="BrandLogo">
                <h1><i class="fa-solid fa-handshake"></i> FundLink</h1>
            </div>
            <div class="UserGreeting">
                <p>Welcome back, <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?> 👋</p>
                <h2>Here's your financial overview</h2>
            </div>
        </header>

        <div class="StatGrid">
            <div class="StatCard bg-blue">
                <i class="fa-solid fa-wallet"></i>
                <p>Wallet Balance</p>
                <h3>৳<?php echo number_format($balance); ?></h3>
            </div>
            <div class="StatCard bg-green">
                <i class="fa-solid fa-chart-line"></i>
                <p>Active Investments</p>
                <h3>৳<?php echo number_format($total_invested); ?></h3>
            </div>
            <div class="StatCard bg-orange">
                <i class="fa-solid fa-hand-holding-dollar"></i>
                <p>Active Borrowing</p>
                <h3>৳<?php echo number_format($total_borrowed); ?></h3>
            </div>
            <div class="StatCard bg-purple">
                <i class="fa-solid fa-percent"></i>
                <p>My Loan Rate (Avg)</p>
                <h3><?php echo number_format($avg_rate, 1); ?>%</h3>
            </div>
        </div>

        <div class="BottomGrid">
            
            
            <div class="ActivityBox" style="margin-right: 20px;">
                <div class="dflex j-between align-center" style="margin-bottom: 20px;">
                    <h4>Others' Loan Requests</h4>
                    <a href="../Loan Marketplace/index.php" style="font-size: 0.9rem; color: #8b5cf6; text-decoration: none;">View All</a>
                </div>

                <?php if (count($market_requests) > 0): ?>
                    <?php foreach ($market_requests as $req): ?>
                        <div class="ActivityItem" style="cursor: pointer;" onclick="window.location.href='../Loan Marketplace/Make_an_Offer.php?id=<?php echo $req['id']; ?>'">
                            <div class="ItemDetails">
                                <div class="IconCircle bg-soft-purple" style="background-color: #e9d5ff; color: #7c3aed;">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div>
                                    <p class="TransactionName"><?php echo htmlspecialchars($req['full_name']); ?></p>
                                    <p class="TransactionMeta"><?php echo htmlspecialchars($req['title']); ?> • <?php echo $req['duration_months']; ?> mo</p>
                                </div>
                            </div>
                            <span class="Amount text-purple" style="color: #7c3aed;">৳<?php echo number_format($req['amount']); ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: #666; text-align: center; margin-top: 20px;">No active requests from others.</p>
                <?php endif; ?>
            </div>

            <div class="ActivityBox">
                <h4>Recent Activity</h4>
                
                <?php if (count($transactions) > 0): ?>
                    <?php foreach ($transactions as $t): ?>
                        <div class="ActivityItem">
                            <div class="ItemDetails">
                                <?php
                                $icon = 'fa-circle';
                                $color_class = 'bg-soft-gray';
                                $text_color = 'text-gray';
                                $sign = '';
                                
                                switch($t['type']) {
                                    case 'deposit':
                                        $icon = 'fa-arrow-down';
                                        $color_class = 'bg-soft-green';
                                        $text_color = 'text-green';
                                        $sign = '+';
                                        break;
                                    case 'withdrawal':
                                        $icon = 'fa-arrow-up';
                                        $color_class = 'bg-soft-red';
                                        $text_color = 'text-red';
                                        $sign = '-';
                                        break;
                                    case 'loan_disbursement':
                                        $icon = 'fa-hand-holding-dollar';
                                        $color_class = 'bg-soft-green';
                                        $text_color = 'text-green';
                                        $sign = '+';
                                        break;
                                    case 'loan_repayment':
                                        $icon = 'fa-money-bill-transfer';
                                        $color_class = 'bg-soft-orange';
                                        $text_color = 'text-red';
                                        $sign = '-';
                                        break;
                                    case 'investment_return':
                                        $icon = 'fa-chart-line';
                                        $color_class = 'bg-soft-blue';
                                        $text_color = 'text-green';
                                        $sign = '+';
                                        break;
                                }
                                ?>
                                <div class="IconCircle <?php echo $color_class; ?>"><i class="fa-solid <?php echo $icon; ?>"></i></div>
                                <div>
                                    <p class="TransactionName"><?php echo ucfirst(str_replace('_', ' ', $t['type'])); ?></p>
                                    <p class="TransactionMeta"><?php echo $t['description']; ?> • <?php echo date('M d, H:i', strtotime($t['created_at'])); ?></p>
                                </div>
                            </div>
                            <span class="Amount <?php echo $text_color; ?>"><?php echo $sign; ?>৳<?php echo number_format(abs($t['amount'])); ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: #666; text-align: center; margin-top: 20px;">No recent activity.</p>
                <?php endif; ?>
                
            </div>

            <div class="ActionsColumn">
                <div class="ActionButton bg-purple">
                    <div>
                        <p class="BtnTitle"> <a href="../Create Loan Request/index.php" style="color: inherit; text-decoration: none;">Request Loan</a></p>
                        <p class="BtnSub">Get competitive offers</p>
                    </div>
                    <i class="fa-solid fa-arrow-right"></i>
                </div>

                <div class="ActionButton bg-green">
                    <div>
                        <p class="BtnTitle"><a href="../Loan Marketplace/index.php" style="color: inherit; text-decoration: none;">Invest Money</a></p>
                        <p class="BtnSub">Browse marketplace</p>
                    </div>
                    <i class="fa-solid fa-arrow-right"></i>
                </div>

                <div class="ActionButton bg-blue">
                    <div>
                        <p class="BtnTitle"><a href="../Wallet Management/index.php" style="color: inherit; text-decoration: none;">My Wallet</a></p>
                        <p class="BtnSub">Manage funds</p>
                    </div>
                    <i class="fa-solid fa-wallet"></i>
                </div>
                
                 <div class="ActionButton bg-orange" style="background-color: #f97316;">
                    <div>
                        <p class="BtnTitle"><a href="../My Loans/index.php" style="color: white; text-decoration: none;">My Loans</a></p>
                        <p class="BtnSub" style="color: rgba(255,255,255,0.8);">Pay installments</p>
                    </div>
                    <i class="fa-solid fa-receipt" style="color: white;"></i>
                </div>
                
                 <div class="ActionButton bg-teal" style="background-color: #14b8a6;">
                    <div>
                        <p class="BtnTitle"><a href="../My Investments/index.php" style="color: white; text-decoration: none;">My Investments</a></p>
                        <p class="BtnSub" style="color: rgba(255,255,255,0.8);">Track returns</p>
                    </div>
                    <i class="fa-solid fa-chart-pie" style="color: white;"></i>
                </div>

                <div class="ActionButton bg-pink" style="background-color: #ec4899;">
                    <div>
                        <p class="BtnTitle"><a href="../Crowdfunding Campaigns/index.php" style="color: white; text-decoration: none;">Crowdfunding</a></p>
                        <p class="BtnSub" style="color: rgba(255,255,255,0.8);">Donate & Create</p>
                    </div>
                    <i class="fa-solid fa-heart" style="color: white;"></i>
                </div>

                <div class="ActionButton bg-purple" style="background-color: #8b5cf6;">
                    <div>
                        <p class="BtnTitle"><a href="../About us/aboutus.php" style="color: white; text-decoration: none;">About Us</a></p>
                        <p class="BtnSub" style="color: rgba(255,255,255,0.8);">Who we are</p>
                    </div>
                    <i class="fa-solid fa-circle-info" style="color: white;"></i>
                </div>

                <div class="ActionButton bg-red" style="background-color: #ef4444; margin-top: auto;">
                    <div>
                        <p class="BtnTitle"><a href="../logout.php" style="color: white; text-decoration: none;">Logout</a></p>
                    </div>
                    <i class="fa-solid fa-right-from-bracket" style="color: white;"></i>
                </div>
            </div>
        </div>

        <!-- NEW: Dashboard Footer -->
        <footer style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e5e7eb; text-align: center; color: #6b7280; width: 100%;">
            <p>&copy; <?php echo date('Y'); ?> FundLink. All rights reserved.</p>
            <div style="margin-top: 10px; display: flex; justify-content: center; gap: 20px; font-size: 0.9rem;">
                <a href="../About us/aboutus.php" style="color: #6b7280; text-decoration: none; font-weight: 500;">About Us</a>
                <span style="color: #d1d5db;">|</span>
                <a href="#" style="color: #6b7280; text-decoration: none;">Privacy Policy</a>
                <span style="color: #d1d5db;">|</span>
                <a href="#" style="color: #6b7280; text-decoration: none;">Terms of Service</a>
            </div>
        </footer>
    </div>

</body>
</html>