<?php include 'wallet_logic.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FundLink - Withdraw Funds</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="Withdraw.css">
</head>
<body>

    <div class="container">
        
        <div class="page-header">
            <span class="breadcrumb"><a href="index.php" style="color: #666; text-decoration: none;">Wallet</a></span>
            <h1>Withdraw Funds</h1>
        </div>

        <?php if($message) echo "<p style='color: " . (strpos($message, 'Error') !== false ? 'red' : 'green') . "; font-weight: bold; background: #fff; padding: 10px; border-radius: 5px; margin-bottom: 20px;'>$message</p>"; ?>

        <div class="content-grid">
            
            <div class="main-column">
                
                <div class="balance-card">
                    <div class="card-top">
                        <div class="balance-label">
                            <i class="fa-solid fa-wallet"></i> Total Balance
                        </div>
                        <h2 class="main-balance">৳<?php echo number_format($balance); ?></h2>
                    </div>

                    <div class="balance-details">
                        <div class="detail-item">
                            <span>Available</span>
                            <strong>৳<?php echo number_format($balance); ?></strong>
                        </div>
                        <div class="detail-item">
                            <span>Locked</span>
                            <strong>৳0</strong>
                        </div>
                        <div class="detail-item">
                            <span>Pending</span>
                            <strong>৳0</strong>
                        </div>
                    </div>

                    <div class="card-actions">
                        <a href="Deposit.php" class="action-btn btn-glass">
                            <i class="fa-solid fa-arrow-down"></i> Deposit
                        </a>
                        <button class="action-btn btn-white">
                            <i class="fa-solid fa-arrow-up"></i> Withdraw
                        </button>
                    </div>
                </div>

                <div class="amount-section">
                    <h3>Enter Withdrawal Amount</h3>
                    <form method="POST">
                        <div class="amount-input-wrapper">
                            <span class="currency-symbol">৳</span>
                            <input type="number" name="withdraw_amount" class="amount-input" placeholder="0.00" required>
                        </div>
                        <button type="submit" class="continue-btn" style="background-color: #ef4444;">Confirm Withdraw <i class="fa-solid fa-arrow-right"></i></button>
                    </form>
                </div>

            </div>

            <div class="side-column">
                <div class="info-card">
                    <h3><i class="fa-solid fa-circle-info"></i> Withdrawal Info</h3>
                    <p>Withdrawals are processed within 1-2 business days. Minimum withdrawal amount is ৳500.</p>
                </div>
            </div>

        </div>
    </div>

</body>
</html>