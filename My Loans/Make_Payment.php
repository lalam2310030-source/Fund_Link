<?php include 'payment_logic.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Payment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="Make_Payment.css">
    <style>
        .alert { padding: 10px; border-radius: 6px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-danger { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

<div class="container">
    
    <div class="back-nav">
        <a href="index.php"><i class="fa-solid fa-arrow-left"></i> Back to My Loans</a>
    </div>

    <div class="header-section">
        <div class="header-main">Make Payment</div>
        <div class="header-sub">Pay your loan installment securely</div>
    </div>

    <?php if($message) echo "<div class='alert alert-success'>$message</div>"; ?>
    <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>

    <div class="payment-layout">

        <div class="left-column">
            
            <div class="card loan-summary-card">
                <div class="card-top-row">
                    <div>
                        <h2 class="card-title"><?php echo htmlspecialchars($loan['title']); ?></h2>
                        <div class="lender-info">
                            <i class="fa-regular fa-user"></i> Lender: <?php echo htmlspecialchars($loan['lender_name']); ?> &nbsp; 
                            <span class="dot">•</span> &nbsp; Interest: <?php echo $loan['interest_rate']; ?>% per year
                        </div>
                    </div>
                    <div class="status-badge"><?php echo ucfirst($loan['status']); ?></div>
                </div>

                <div class="progress-section">
                    <div class="progress-labels">
                        <span>Payment Progress</span>
                        <span class="highlight-purple"><?php echo $percent; ?>% paid</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php echo $percent; ?>%;"></div>
                        <div class="progress-text">৳<?php echo number_format($total_paid); ?> of ৳<?php echo number_format($total_due); ?> paid</div>
                    </div>
                </div>

                <div class="loan-stats-grid">
                    <div class="stat-box">
                        <span class="stat-label">TOTAL LOAN</span>
                        <span class="stat-num">৳<?php echo number_format($total_due); ?></span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-label">PAID</span>
                        <span class="stat-num text-green">৳<?php echo number_format($total_paid); ?></span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-label">REMAINING</span>
                        <span class="stat-num text-purple">৳<?php echo number_format($remaining); ?></span>
                    </div>
                </div>
            </div>

                <?php if ($repayment): ?>
                <div class="card payment-details-card">
                    <h3 class="section-title">Payment Details</h3>

                    <form method="POST">
                        <input type="hidden" name="loan_id" value="<?php echo $loan['id']; ?>">
                        <input type="hidden" name="pay_installment" value="1">
                        
                        <div class="input-group">
                            <label>Payment Amount (৳)</label>
                            <div class="amount-input-wrapper">
                                <span class="currency-symbol">৳</span>
                                <input type="text" value="<?php echo number_format($repayment['amount']); ?>" class="amount-input" disabled>
                            </div>
                            <div class="input-helper">
                                <span>Next installment due: <?php echo date('M d, Y', strtotime($repayment['due_date'])); ?></span>
                            </div>
                        </div>

                        <label class="method-label">Payment Method</label>
                        <div class="methods-grid">
                            <div class="method-card selected">
                                <div class="method-icon icon-bkash"><i class="fa-solid fa-wallet"></i></div>
                                <div class="method-info">
                                    <h4>My Wallet</h4>
                                    <span>Balance: ৳<?php 
                                        $b_sql = "SELECT balance FROM users WHERE id = $user_id";
                                        $b_res = $conn->query($b_sql);
                                        echo number_format($b_res->fetch_assoc()['balance']);
                                    ?></span>
                                </div>
                                <div class="check-icon"><i class="fa-solid fa-check"></i></div>
                            </div>
                        </div>

                        <div class="info-box">
                            <i class="fa-solid fa-shield-halved"></i>
                            <p>Payments are secure and encrypted. Late payments may incur additional fees.</p>
                        </div>

                        <div class="action-buttons">
                            <a href="index.php" class="btn-cancel">Cancel</a>
                            <button type="submit" class="btn-pay">Confirm Payment <i class="fa-solid fa-arrow-right"></i></button>
                        </div>
                    </form>
                </div>
                <?php else: ?>
                    <div class="alert alert-success">All installments for this loan have been paid!</div>
                <?php endif; ?>
            </div>

            <div class="right-column">
                <div class="card summary-card">
                    <h3 class="section-title">Payment Summary</h3>
                    
                    <div class="summary-row">
                        <span>Installment Amount</span>
                        <span>৳<?php echo $repayment ? number_format($repayment['amount']) : '0'; ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Late Fees</span>
                        <span>৳0</span>
                    </div>
                    <div class="summary-row">
                        <span>Processing Fee</span>
                        <span>৳0</span>
                    </div>
                    <div class="divider"></div>
                    <div class="summary-row total-row">
                        <span>Total Payable</span>
                        <span>৳<?php echo $repayment ? number_format($repayment['amount']) : '0'; ?></span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
