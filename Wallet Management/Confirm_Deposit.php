<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FundLink - Deposit Confirmation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="Confirm_Deposit.css">
</head>
<body>

    <div class="container">
        
        <div class="page-header">
            <span class="breadcrumb">Wallet</span>
            <h1>Manage your funds</h1>
        </div>

        <div class="content-grid">
            
            <div class="main-column">
                
                <div class="balance-card">
                    <div class="card-top">
                        <div class="balance-label">
                            <i class="fa-solid fa-wallet"></i> Total Balance
                        </div>
                        <h2 class="main-balance">৳85,500</h2>
                    </div>

                    <div class="balance-details">
                        <div class="detail-item">
                            <span>Available</span>
                            <strong>৳65,500</strong>
                        </div>
                        <div class="detail-item">
                            <span>Locked</span>
                            <strong>৳20,000</strong>
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
                        <a href="withdraw.html" class="action-btn btn-glass">
                            <i class="fa-solid fa-arrow-up"></i> Withdraw
                        </a>
                    </div>
                </div>

                <div class="transactions-section">
                    <h3>Recent Transactions</h3>
                    
                    <div class="trans-list">
                        <div class="trans-item">
                            <div class="trans-left">
                                <div class="icon-circle icon-green">
                                    <i class="fa-solid fa-arrow-down"></i>
                                </div>
                                <div class="trans-info">
                                    <h4>Deposit</h4>
                                    <p>Dec 2, 2025 • Bank Transfer</p>
                                </div>
                            </div>
                            <div class="trans-right">
                                <span class="amount positive">+৳25,000</span>
                                <span class="status status-pending">Pending</span>
                            </div>
                        </div>

                        <div class="trans-item">
                            <div class="trans-left">
                                <div class="icon-circle icon-green">
                                    <i class="fa-solid fa-arrow-down"></i>
                                </div>
                                <div class="trans-info">
                                    <h4>Deposit</h4>
                                    <p>Nov 28, 2025 • bKash</p>
                                </div>
                            </div>
                            <div class="trans-right">
                                <span class="amount positive">+৳10,000</span>
                                <span class="status status-completed">Completed</span>
                            </div>
                        </div>

                        <div class="trans-item">
                            <div class="trans-left">
                                <div class="icon-circle icon-red">
                                    <i class="fa-solid fa-arrow-up"></i>
                                </div>
                                <div class="trans-info">
                                    <h4>Withdraw</h4>
                                    <p>Nov 25, 2025 • Nagad</p>
                                </div>
                            </div>
                            <div class="trans-right">
                                <span class="amount negative">-৳5,000</span>
                                <span class="status status-completed">Completed</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sidebar-column">
                <div class="confirmation-card">
                    
                    <div class="confirm-header">
                        <div class="check-circle">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <h3>Deposit Initiated</h3>
                        <p>Please complete the payment using the details below</p>
                    </div>

                    <div class="confirm-section">
                        <label>Amount</label>
                        <div class="confirm-amount">৳10,000</div>
                    </div>

                    <div class="confirm-section">
                        <label>Payment Method</label>
                        <div class="method-display">
                            <i class="fa-solid fa-building-columns"></i> Bank Transfer
                        </div>
                    </div>

                    <div class="bank-details-box">
                        <div class="details-header">Bank Account Details</div>
                        
                        <div class="detail-row">
                            <span class="d-label">Bank Name:</span>
                            <span class="d-value">Islami Bank Bangladesh</span>
                        </div>
                        
                        <div class="detail-row">
                            <span class="d-label">Account Name:</span>
                            <span class="d-value">FundLink Ltd.</span>
                        </div>

                        <div class="detail-row">
                            <span class="d-label">Account Number:</span>
                            <div class="d-value-group">
                                <strong>20501290201458</strong>
                                <i class="fa-regular fa-copy copy-btn"></i>
                            </div>
                        </div>

                        <div class="detail-row">
                            <span class="d-label">Reference ID:</span>
                            <div class="d-value-group">
                                <strong class="ref-id">TXN20250112A4B</strong>
                                <i class="fa-regular fa-copy copy-btn ref-copy"></i>
                            </div>
                        </div>
                    </div>

                    <div class="info-notice">
                        <i class="fa-solid fa-shield-halved"></i>
                        <div>
                            <strong>Important</strong>
                            <p>Please include the Reference ID in your transfer. Funds will be credited within 1-2 business days.</p>
                        </div>
                    </div>

                    <div class="processing-time">
                        <i class="fa-regular fa-clock"></i> Processing Time: 1-2 business days
                    </div>

                    <div class="confirm-actions">
                        <button class="btn-primary-gradient">I've Made the Payment</button>
                        <button class="btn-outline">Download Instructions</button>
                    </div>

                </div>
            </div>

        </div>
    </div>

</body>
</html>