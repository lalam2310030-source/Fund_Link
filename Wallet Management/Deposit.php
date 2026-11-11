<?php include 'wallet_logic.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FundLink - Wallet & Deposit</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="Diposit.css">
</head>
<body>

    <div class="container">
        
        <div class="page-header">
            <span class="breadcrumb"><a href="index.php" style="color: #666; text-decoration: none;">Wallet</a></span>
            <h1>Deposit Funds</h1>
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
                        <button class="action-btn btn-white">
                            <i class="fa-solid fa-arrow-down"></i> Deposit
                        </button>
                        <a href="Withdraw.php" class="action-btn btn-glass">
                            <i class="fa-solid fa-arrow-up"></i> Withdraw
                        </a>
                    </div>
                </div>

                <div class="deposit-methods">
                    <h3>Select Deposit Method</h3>
                    <div class="methods-grid">
                        <div class="method-card active">
                            <div class="method-icon">
                                <i class="fa-solid fa-building-columns"></i>
                            </div>
                            <div class="method-info">
                                <h4>Bank Transfer</h4>
                                <p>Direct bank deposit</p>
                            </div>
                            <div class="check-icon">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                        </div>

                        <div class="method-card">
                            <div class="method-icon">
                                <i class="fa-solid fa-credit-card"></i>
                            </div>
                            <div class="method-info">
                                <h4>Card Payment</h4>
                                <p>Visa, Mastercard</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="amount-section">
                    <h3>Enter Amount</h3>
                    <form method="POST">
                        <div class="amount-input-wrapper">
                            <span class="currency-symbol">৳</span>
                            <input type="number" name="deposit_amount" class="amount-input" placeholder="0.00" required>
                        </div>
                        <button type="submit" class="continue-btn">Confirm Deposit <i class="fa-solid fa-arrow-right"></i></button>
                    </form>
                </div>

            </div>

            <div class="side-column">
                <div class="info-card">
                    <h3><i class="fa-solid fa-circle-info"></i> Important Note</h3>
                    <p>Deposits are usually processed instantly. For bank transfers, it may take up to 24 hours.</p>
                </div>
            </div>

        </div>
    </div>

</body>
</html>