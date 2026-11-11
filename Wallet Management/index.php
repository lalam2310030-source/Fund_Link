<?php include 'wallet_logic.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallet Management</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="MainWrapper">
        <div class="TopNav" style="margin-bottom: 20px;">
            <a href="../Dashboard/index.php" style="text-decoration: none; color: #333;"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
        </div>
        <div class="Top">
            <p><strong>Wallet</strong></p>
            <h5>Manage your funds</h5>
        </div>

        <?php if($message) echo "<p style='color: green; font-weight: bold; background: #d4edda; padding: 10px; border-radius: 5px;'>$message</p>"; ?>

        <div class="Middle dflex">
            <div class="Mid_Left">
                <div class="BalanceCard">
                    <p><i class="fa-solid fa-money-bill"></i> <strong>Total Balance</strong></p>
                    <h1>৳<?php echo number_format($balance); ?></h1>

                    <div class="StatsRow dflex">
                        <div class="stat">
                            <p>Available</p>
                            <h2>৳<?php echo number_format($balance); ?></h2>
                        </div>
                        <div class="stat">
                            <p>Locked</p>
                            <h2>৳0</h2>
                        </div>
                        <div class="stat">
                            <p>Pending</p>
                            <h2>৳0</h2>
                        </div>
                    </div>

                    <div class="ActionBtns dflex" style="flex-wrap: wrap; gap: 10px;">
                        <button class="btn-deposit"><i class="fa-solid fa-arrow-down"></i><a href="Deposit.php" style="color: inherit; text-decoration:none;">Deposit</a></button>
                        <button class="btn-withdraw"><i class="fa-solid fa-arrow-up"></i><a href="Withdraw.php" style="color: inherit; text-decoration:none;">Withdraw</a></button>
                        
                        <button class="btn-withdraw" style="border-color: rgba(255,255,255,0.5) !important;">
                            <i class="fa-solid fa-bullhorn"></i>
                            <a href="../Crowdfunding Campaigns/CreateNewCampaign.php" style="color: inherit; text-decoration:none;">Start Campaign</a>
                        </button>
                        <button class="btn-withdraw" style="border-color: rgba(255,255,255,0.5) !important;">
                            <i class="fa-solid fa-hand-holding-heart"></i>
                            <a href="../Crowdfunding Campaigns/SupportCampaign.php" style="color: inherit; text-decoration:none;">Support</a>
                        </button>
                    </div>
                </div>
            </div>

            <div class="Mid_Right">
                <form class="DepositForm" method="POST">
                    <p><strong>Quick Deposit (Test Mode)</strong></p>
                    <label>Amount</label>
                    <input type="number" name="deposit_amount" placeholder="e.g. 5000" required>
                    
                    <button type="button" class="method-btn active">
                        <i class="fa-solid fa-building-columns"></i>
                        <div>
                            <span>Bank Transfer</span>
                            <p>Direct deposit</p>
                        </div>
                    </button>

                    <button type="button" class="method-btn">
                        <i class="fa-solid fa-mobile-screen"></i>
                        <div>
                            <span>Mobile Banking</span>
                            <p>bKash, Nagad, Rocket</p>
                        </div>
                    </button>

                    <button type="submit" class="btn-confirm" style="width: 100%; margin-top: 10px; cursor: pointer;">Confirm Deposit</button>
                </form>
            </div>
        </div>

        <div class="lower">
            <p class="section-title">Recent Transactions</p>
            <div class="TransactionList">
                <?php if (count($transactions) > 0): ?>
                    <?php foreach ($transactions as $t): ?>
                    <div class="TransItem dflex">
                        <div class="dflex">
                            <div class="icon <?php echo ($t['type'] == 'deposit' || $t['type'] == 'loan_disbursement' || $t['type'] == 'investment_return') ? 'green' : 'red'; ?>">
                                <i class="fa-solid <?php echo ($t['type'] == 'deposit') ? 'fa-arrow-down' : 'fa-arrow-up'; ?>"></i>
                            </div>
                            <div>
                                <p><strong><?php echo ucfirst(str_replace('_', ' ', $t['type'])); ?></strong></p>
                                <span><?php echo $t['description']; ?> - <?php echo date('M d, Y', strtotime($t['created_at'])); ?></span>
                            </div>
                        </div>
                        <p><strong><?php echo ($t['type'] == 'deposit' || $t['type'] == 'loan_disbursement' || $t['type'] == 'investment_return') ? '+' : '-'; ?>৳<?php echo number_format($t['amount']); ?></strong></p>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="TransItem dflex">
                        <div class="dflex">
                            <div class="icon green"><i class="fa-solid fa-info"></i></div>
                            <div>
                                <p><strong>No transactions yet</strong></p>
                                <span>Start by depositing funds.</span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>