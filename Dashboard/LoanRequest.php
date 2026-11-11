<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FundLink - Request a Loan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="LoanRequest.css"> 
</head>
<body>

    <div class="container">

        <header class="top-header">
            <div class="header-left">
                <h1>Request a Loan</h1>
                <p>Create your loan request and get competitive offers from lenders</p>
            </div>
            <div class="header-right">
                <span>Logged in as</span>
                <strong>Mehedi Hasan Rafat</strong>
            </div>
        </header>

        <div class="main-content">
            
            <div class="form-card">
                <h2>Loan Details</h2>

                <div class="form-group">
                    <label>Loan Amount (&#2547;)</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-dollar-sign"></i>
                        <input type="number" placeholder="Enter amount in Taka">
                        <div class="arrows">
                            <i class="fa-solid fa-caret-up"></i>
                            <i class="fa-solid fa-caret-down"></i>
                        </div>
                    </div>
                    <span class="hint">Minimum: &#2547;5,000 - Maximum: &#2547;500,000</span>
                </div>

                <div class="form-group">
                    <label>Loan Duration</label>
                    <div class="input-wrapper">
                        <i class="fa-regular fa-calendar"></i>
                        <select>
                            <option>Select duration</option>
                            <option>3 Months</option>
                            <option>6 Months</option>
                            <option>12 Months</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Maximum Interest Rate You'll Accept (% per year)</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-percent"></i>
                        <input type="number" placeholder="e.g., 12">
                    </div>
                    <span class="hint">Lenders can offer lower rates to compete</span>
                </div>

                <div class="form-group">
                    <label>Loan Purpose</label>
                    <div class="input-wrapper text-area-wrapper">
                        <i class="fa-solid fa-file-lines"></i>
                        <textarea placeholder="Describe why you need this loan and how you plan to use it..."></textarea>
                    </div>
                    <span class="hint">Be specific to attract more lenders</span>
                </div>

                <div class="form-group">
                    <label>Preferred Repayment Method</label>
                    <div class="repayment-box">
                        <div class="radio-option active">
                            <h4>Monthly Installments</h4>
                            <p>Pay equal amounts each month</p>
                        </div>
                        <div class="radio-option">
                            <h4>Lump Sum at End</h4>
                            <p>Pay everything at maturity</p>
                        </div>
                    </div>
                </div>

                <button class="btn-submit">Submit Loan Request</button>
            </div>

            <div class="sidebar-widgets">
                
                <div class="widget purple-card">
                    <div class="widget-title">
                        <i class="fa-solid fa-arrow-trend-up"></i>
                        <h3>How It Works</h3>
                    </div>
                    <ul class="steps">
                        <li>
                            <span class="num">1</span>
                            <div class="step-text">
                                <strong>Submit Request</strong>
                                <p>Fill in your loan details</p>
                            </div>
                        </li>
                        <li>
                            <span class="num">2</span>
                            <div class="step-text">
                                <strong>Get Offers</strong>
                                <p>Lenders compete with rates</p>
                            </div>
                        </li>
                        <li>
                            <span class="num">3</span>
                            <div class="step-text">
                                <strong>Choose Best Deal</strong>
                                <p>Accept the offer you like</p>
                            </div>
                        </li>
                        <li>
                            <span class="num">4</span>
                            <div class="step-text">
                                <strong>Receive Money</strong>
                                <p>Funds sent to your wallet</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="widget white-card">
                    <div class="widget-title light">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <h3>Tips</h3>
                    </div>
                    <ul class="tips">
                        <li>Be honest about your loan purpose</li>
                        <li>Lower max interest rates attract more offers</li>
                        <li>Shorter durations get better rates</li>
                        <li>Check offers within 24 hours</li>
                    </ul>
                </div>

                <div class="widget green-card">
                    <h3>Current Market</h3>
                    <div class="stat">
                        <p>Average Interest Rate</p>
                        <h2>8.5%</h2>
                    </div>
                    <div class="stat">
                        <p>Active Lenders</p>
                        <h2>1,247</h2>
                    </div>
                    <div class="stat">
                        <p>Avg. Response Time</p>
                        <h2>4 hours</h2>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>