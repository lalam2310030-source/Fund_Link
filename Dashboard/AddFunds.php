<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FundLink - Add Funds</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="AddFunds.css">
</head>

<body>

    <div class="container">

        <header class="top-header">
            <div class="header-left">
                <h1>Add Funds to Wallet</h1>
                <p>Deposit money to start investing or withdraw your earnings</p>
            </div>
            <div class="header-right">
                <span>Logged in as</span>
                <strong>Hafizur_Sadik</strong>
            </div>
        </header>

        <div class="content-grid">

            <main class="main-column">

                <div class="wallet-card">
                    <div class="wallet-info">
                        <div class="wallet-label">
                            <i class="fa-solid fa-wallet"></i> Available Balance
                        </div>
                        <h2 class="balance">&#2547;85,500</h2>
                        <div class="wallet-stats">
                            <div>
                                <span>Total Deposited</span>
                                <strong>&#2547;650,000</strong>
                            </div>
                            <div>
                                <span>Total Withdrawn</span>
                                <strong>&#2547;564,500</strong>
                            </div>
                        </div>
                    </div>
                    <div class="wallet-graph">
                        <div class="graph-circle">
                            <i class="fa-solid fa-arrow-trend-up"></i>
                        </div>
                        <p>Last 30 days</p>
                        <span class="trend">+12.5%</span>
                    </div>
                </div>

                <div class="payment-section">
                    <h3>Choose Payment Method</h3>

                    <div class="payment-grid">
                        <div class="payment-option active">
                            <div class="payment-top">
                                <div class="icon-box bkash-bg">
                                    <i class="fa-solid fa-mobile-screen"></i>
                                </div>
                                <i class="fa-solid fa-arrow-right arrow-icon"></i>
                            </div>
                            <h4>bKash</h4>
                            <p>Instant transfer via mobile</p>
                            <div class="payment-meta">
                                <span class="check"><i class="fa-solid fa-check"></i> Instant</span>
                                <span class="fee">• Fee: &#2547;0</span>
                            </div>
                        </div>

                        <div class="payment-option">
                            <div class="payment-top">
                                <div class="icon-box nagad-bg">
                                    <i class="fa-solid fa-mobile-screen"></i>
                                </div>
                                <i class="fa-solid fa-arrow-right arrow-icon"></i>
                            </div>
                            <h4>Nagad</h4>
                            <p>Fast mobile payment</p>
                            <div class="payment-meta">
                                <span class="check"><i class="fa-solid fa-check"></i> Instant</span>
                                <span class="fee">• Fee: &#2547;0</span>
                            </div>
                        </div>

                        <div class="payment-option">
                            <div class="payment-top">
                                <div class="icon-box rocket-bg">
                                    <i class="fa-solid fa-mobile-screen"></i>
                                </div>
                                <i class="fa-solid fa-arrow-right arrow-icon"></i>
                            </div>
                            <h4>Rocket</h4>
                            <p>DBBL mobile banking</p>
                            <div class="payment-meta">
                                <span class="check"><i class="fa-solid fa-check"></i> Instant</span>
                                <span class="fee">• Fee: &#2547;0</span>
                            </div>
                        </div>

                        <div class="payment-option">
                            <div class="payment-top">
                                <div class="icon-box bank-bg">
                                    <i class="fa-solid fa-building-columns"></i>
                                </div>
                                <i class="fa-solid fa-arrow-right arrow-icon"></i>
                            </div>
                            <h4>Bank Transfer</h4>
                            <p>Direct bank deposit</p>
                            <div class="payment-meta warning">
                                <span class="time"><i class="fa-regular fa-clock"></i> 1-2 days</span>
                                <span class="fee">• Fee: &#2547;50</span>
                            </div>
                        </div>

                        <div class="payment-option">
                            <div class="payment-top">
                                <div class="icon-box card-bg">
                                    <i class="fa-regular fa-credit-card"></i>
                                </div>
                                <i class="fa-solid fa-arrow-right arrow-icon"></i>
                            </div>
                            <h4>Credit/Debit Card</h4>
                            <p>Visa, Mastercard accepted</p>
                            <div class="payment-meta">
                                <span class="check"><i class="fa-solid fa-check"></i> Instant</span>
                                <span class="fee">• Fee: 2.5%</span>
                            </div>
                        </div>
                    </div>

                    <div class="amount-section">
                        <label>Enter Amount (&#2547;)</label>
                        <input type="number" placeholder="Enter amount to deposit" class="amount-input">

                        <div class="quick-amounts">
                            <button>&#2547;5,000</button>
                            <button>&#2547;10,000</button>
                            <button>&#2547;25,000</button>
                            <button>&#2547;50,000</button>
                        </div>

                        <button class="btn-continue">Continue to Payment</button>
                    </div>

                </div>
            </main>

            <aside class="sidebar">

                <div class="sidebar-card secure-card">
                    <div class="secure-header">
                        <i class="fa-solid fa-shield-halved"></i>
                        <h3>100% Secure</h3>
                    </div>
                    <ul class="secure-list">
                        <li><i class="fa-solid fa-check"></i> Bank-level encryption</li>
                        <li><i class="fa-solid fa-check"></i> PCI DSS compliant</li>
                        <li><i class="fa-solid fa-check"></i> Two-factor authentication</li>
                        <li><i class="fa-solid fa-check"></i> Fraud detection system</li>
                    </ul>
                </div>

                <div class="sidebar-card history-card">
                    <h3>Recent Transactions</h3>

                    <div class="transaction-item">
                        <div class="trans-info">
                            <h4>Deposit</h4>
                            <p>bKash</p>
                            <span class="date">Jan 10, 2026</span>
                        </div>
                        <div class="trans-amount positive">
                            <strong>+&#2547;25,000</strong>
                            <span class="status completed">completed</span>
                        </div>
                    </div>

                    <div class="transaction-item">
                        <div class="trans-info">
                            <h4>Deposit</h4>
                            <p>Bank Transfer</p>
                            <span class="date">Jan 8, 2026</span>
                        </div>
                        <div class="trans-amount positive">
                            <strong>+&#2547;50,000</strong>
                            <span class="status completed">completed</span>
                        </div>
                    </div>

                    <div class="transaction-item">
                        <div class="trans-info">
                            <h4>Withdrawal</h4>
                            <p>Nagad</p>
                            <span class="date">Jan 5, 2026</span>
                        </div>
                        <div class="trans-amount negative">
                            <strong>-&#2547;15,000</strong>
                            <span class="status completed">completed</span>
                        </div>
                    </div>
                </div>

                <button class="btn-withdraw">Withdraw Money</button>

                <div class="sidebar-card limits-card">
                    <h3>Deposit Limits</h3>
                    <div class="limit-row">
                        <span>Minimum:</span>
                        <strong>&#2547;500</strong>
                    </div>
                    <div class="limit-row">
                        <span>Maximum per day:</span>
                        <strong>&#2547;500,000</strong>
                    </div>
                    <div class="limit-row">
                        <span>Maximum per month:</span>
                        <strong>&#2547;2,000,000</strong>
                    </div>
                </div>

            </aside>
        </div>
    </div>

</body>

</html>