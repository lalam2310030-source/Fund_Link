<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FundLink - Loan Marketplace</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="InvestMoney.css">
</head>

<body>

    <div class="container">

        <header class="top-header">
            <div class="header-left">
                <h1>Loan Marketplace</h1>
                <p>Browse active loan requests and make offers to borrowers.</p>
            </div>
            <div class="header-right">
                <span>Logged in as</span>
                <strong>Mehedi Hasan Rafat</strong>
            </div>
        </header>

        <div class="marketplace-layout">

            <aside class="sidebar">

                <div class="sidebar-card gradient-card">
                    <p class="label">Total Invested</p>
                    <h2>&#2547;450,000</h2>
                    <div class="sub-stat">
                        <p>Avg. Return Rate</p>
                        <strong>9.2%</strong>
                    </div>
                </div>

                <div class="sidebar-card filter-card">
                    <h3>Filters</h3>

                    <div class="filter-group">
                        <label>Loan Amount (&#2547;)</label>
                        <input type="number" placeholder="Min">
                        <input type="number" placeholder="Max" style="margin-top: 10px;">
                    </div>

                    <div class="filter-group">
                        <label>Duration</label>
                        <select>
                            <option>All Durations</option>
                            <option>6 Months</option>
                            <option>12 Months</option>
                            <option>24 Months</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Max Interest Rate</label>
                        <select>
                            <option>All Rates</option>
                            <option>Under 10%</option>
                            <option>10% - 15%</option>
                        </select>
                    </div>

                    <button class="btn-filter">Apply Filters</button>
                </div>

                <div class="sidebar-card note-card">
                    <div class="note-header">
                        <i class="fa-solid fa-shield-halved"></i>
                        <h4>Investment Note</h4>
                    </div>
                    <p>Only invest what you can afford. Diversify across multiple loans to reduce risk.</p>
                </div>
            </aside>

            <main class="listings">

                <div class="loan-card">
                    <div class="card-top">
                        <div class="user-info">
                            <div class="avatar purple">N</div>
                            <div>
                                <h3>Nusrat Jahan <span class="badge-hot"><i class="fa-solid fa-fire"></i> HOT</span>
                                </h3>
                                <p class="purpose">Medical expenses for family member treatment</p>
                            </div>
                        </div>
                        <div class="amount-info">
                            <h2>&#2547;100,000</h2>
                            <p>Requested Amount</p>
                        </div>
                    </div>

                    <div class="card-stats">
                        <div class="stat-box">
                            <div class="icon-box icon-purple"><i class="fa-solid fa-percent"></i></div>
                            <div>
                                <span class="label">Max Rate</span>
                                <strong>12%</strong>
                            </div>
                        </div>
                        <div class="stat-box">
                            <div class="icon-box icon-blue"><i class="fa-regular fa-calendar"></i></div>
                            <div>
                                <span class="label">Duration</span>
                                <strong>6 Months</strong>
                            </div>
                        </div>
                        <div class="stat-box">
                            <div class="icon-box icon-orange"><i class="fa-regular fa-clock"></i></div>
                            <div>
                                <span class="label">Time Left</span>
                                <strong>5 hours left</strong>
                            </div>
                        </div>
                        <div class="stat-box">
                            <div class="icon-box icon-green"><i class="fa-regular fa-user"></i></div>
                            <div>
                                <span class="label">Offers</span>
                                <strong>15</strong>
                            </div>
                        </div>
                    </div>

                    <div class="card-actions">
                        <button class="btn-offer">Make an Offer</button>
                        <button class="btn-view">View Details</button>
                    </div>
                </div>

                <div class="loan-card">
                    <div class="card-top">
                        <div class="user-info">
                            <div class="avatar blue">K</div>
                            <div>
                                <h3>Kamal Hossain</h3>
                                <p class="purpose">Education fees for university semester and books</p>
                            </div>
                        </div>
                        <div class="amount-info">
                            <h2>&#2547;75,000</h2>
                            <p>Requested Amount</p>
                        </div>
                    </div>

                    <div class="card-stats">
                        <div class="stat-box">
                            <div class="icon-box icon-purple"><i class="fa-solid fa-percent"></i></div>
                            <div>
                                <span class="label">Max Rate</span>
                                <strong>9%</strong>
                            </div>
                        </div>
                        <div class="stat-box">
                            <div class="icon-box icon-blue"><i class="fa-regular fa-calendar"></i></div>
                            <div>
                                <span class="label">Duration</span>
                                <strong>18 Months</strong>
                            </div>
                        </div>
                        <div class="stat-box">
                            <div class="icon-box icon-orange"><i class="fa-regular fa-clock"></i></div>
                            <div>
                                <span class="label">Time Left</span>
                                <strong>1 day left</strong>
                            </div>
                        </div>
                        <div class="stat-box">
                            <div class="icon-box icon-green"><i class="fa-regular fa-user"></i></div>
                            <div>
                                <span class="label">Offers</span>
                                <strong>12</strong>
                            </div>
                        </div>
                    </div>

                    <div class="card-actions">
                        <button class="btn-offer">Make an Offer</button>
                        <button class="btn-view">View Details</button>
                    </div>
                </div>

                <div class="loan-card">
                    <div class="card-top">
                        <div class="user-info">
                            <div class="avatar purple">R</div>
                            <div>
                                <h3>Rupa Begum</h3>
                                <p class="purpose">Home renovation - repairing roof before monsoon season</p>
                            </div>
                        </div>
                        <div class="amount-info">
                            <h2>&#2547;30,000</h2>
                            <p>Requested Amount</p>
                        </div>
                    </div>

                    <div class="card-stats">
                        <div class="stat-box">
                            <div class="icon-box icon-purple"><i class="fa-solid fa-percent"></i></div>
                            <div>
                                <span class="label">Max Rate</span>
                                <strong>11.5%</strong>
                            </div>
                        </div>
                        <div class="stat-box">
                            <div class="icon-box icon-blue"><i class="fa-regular fa-calendar"></i></div>
                            <div>
                                <span class="label">Duration</span>
                                <strong>9 Months</strong>
                            </div>
                        </div>
                        <div class="stat-box">
                            <div class="icon-box icon-orange"><i class="fa-regular fa-clock"></i></div>
                            <div>
                                <span class="label">Time Left</span>
                                <strong>3 days left</strong>
                            </div>
                        </div>
                        <div class="stat-box">
                            <div class="icon-box icon-green"><i class="fa-regular fa-user"></i></div>
                            <div>
                                <span class="label">Offers</span>
                                <strong>6</strong>
                            </div>
                        </div>
                    </div>

                    <div class="card-actions">
                        <button class="btn-offer">Make an Offer</button>
                        <button class="btn-view">View Details</button>
                    </div>
                </div>

                <div class="load-more-container">
                    <button class="btn-load-more">Load More Requests</button>
                </div>

            </main>
        </div>
    </div>

</body>

</html>