<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login_&_Register/index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

if (isset($_GET['id'])) {
    $request_id = $_GET['id'];
    
    
    $stmt = $conn->prepare("SELECT lr.*, u.full_name, u.created_at as member_since,
                            (SELECT COUNT(*) FROM loan_offers WHERE loan_request_id = lr.id) as offer_count,
                            (SELECT MIN(interest_rate) FROM loan_offers WHERE loan_request_id = lr.id) as best_rate
                            FROM loan_requests lr 
                            JOIN users u ON lr.user_id = u.id 
                            WHERE lr.id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $req = $result->fetch_assoc();
    } else {
        die("Loan request not found.");
    }
} else {
    die("Invalid request.");
}


$stmt_bal = $conn->prepare("SELECT balance FROM users WHERE id = ?");
$stmt_bal->bind_param("i", $user_id);
$stmt_bal->execute();
$res_bal = $stmt_bal->get_result();
$user_balance = $res_bal->fetch_assoc()['balance'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $interest_rate = $_POST['interest_rate'];
    
   
    if ($user_balance >= $req['amount']) {
        if ($interest_rate <= $req['max_interest_rate'] && $interest_rate > 0) {
            $stmt = $conn->prepare("INSERT INTO loan_offers (loan_request_id, lender_user_id, interest_rate, status) VALUES (?, ?, ?, 'pending')");
            $stmt->bind_param("iid", $request_id, $user_id, $interest_rate);
            
            if ($stmt->execute()) {
                $message = "Offer submitted successfully!";
            } else {
                $message = "Error: " . $conn->error;
            }
        } else {
            $message = "Interest rate must be between 0 and " . $req['max_interest_rate'] . "%";
        }
    } else {
        $message = "Insufficient balance. You need ৳" . number_format($req['amount']) . " to make an offer.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make an Offer</title>
    <link rel="stylesheet" href="Make_an_Offer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="MainWrapper">
        
        <div class="TopNav">
            <a href="index.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Marketplace</a>
        </div>

        <div class="Top">
            <h5>Make an Offer</h5>
            <p>Submit your competitive offer to fund this loan request</p>
        </div>

        <?php if($message) echo "<p style='color: " . (strpos($message, 'Error') !== false || strpos($message, 'Insufficient') !== false ? 'red' : 'green') . "; font-weight: bold; background: #fff; padding: 10px; border-radius: 5px; margin-bottom: 20px;'>$message</p>"; ?>

        <div class="ContentSplit dflex">
            
            <div class="LeftCol">
                
                <div class="LoanInfoCard">
                    <div class="BorrowerHeader dflex">
                        <div class="avatar"><?php echo strtoupper(substr($req['full_name'], 0, 1)); ?></div>
                        <div class="b-info">
                            <div class="name-row">
                                <h3><?php echo htmlspecialchars($req['full_name']); ?></h3>
                                <span class="badge-open"><?php echo ucfirst($req['status']); ?></span>
                            </div>
                            <p class="member-since">Member since <?php echo date('Y', strtotime($req['member_since'])); ?></p>
                            <h4><?php echo htmlspecialchars($req['title']); ?></h4>
                            <p class="desc"><?php echo htmlspecialchars($req['description']); ?></p>
                        </div>
                    </div>

                    <div class="LoanStats dflex">
                        <div class="l-stat">
                            <label>Requested Amount</label>
                            <h3>৳<?php echo number_format($req['amount']); ?></h3>
                        </div>
                        <div class="l-stat">
                            <label>Duration</label>
                            <h3><?php echo $req['duration_months']; ?> months</h3>
                        </div>
                        <div class="l-stat">
                            <label>Max Interest Rate</label>
                            <h3 class="purple-text"><?php echo $req['max_interest_rate']; ?>%</h3>
                        </div>
                        <div class="l-stat">
                            <label>Current Offers</label>
                            <h3><?php echo $req['offer_count']; ?></h3>
                            <small class="best-offer"><?php echo $req['best_rate'] ? 'Best: ' . $req['best_rate'] . '%' : 'No offers yet'; ?></small>
                        </div>
                    </div>
                </div>

                <div class="OfferFormSection">
                    <h3>Your Offer Details</h3>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label>Your Interest Rate (% per year)</label>
                            <div class="input-icon-wrapper">
                                <i class="fa-solid fa-percent"></i>
                                <input type="number" name="interest_rate" placeholder="e.g., 9.0" step="0.1" max="<?php echo $req['max_interest_rate']; ?>" required>
                            </div>
                            <div class="input-helper dflex">
                                <span>Must be at or below <?php echo $req['max_interest_rate']; ?>%</span>
                                <span class="purple-text"><?php echo $req['best_rate'] ? 'Current best offer: ' . $req['best_rate'] . '%' : ''; ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Loan Amount (৳)</label>
                            <div class="input-icon-wrapper">
                                <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                                <input type="text" value="<?php echo number_format($req['amount']); ?>" disabled class="disabled-input">
                            </div>
                            <span class="helper-text">Requested amount (cannot be changed)</span>
                        </div>

                        <div class="OfferSummaryBox">
                            <p class="summary-title"><i class="fa-solid fa-chart-line"></i> Offer Summary</p>
                            
                            <div class="summary-row">
                                <span>Principal Amount:</span>
                                <span>৳<?php echo number_format($req['amount']); ?></span>
                            </div>
                            <div class="summary-row">
                                <span>Your Interest Rate:</span>
                                <span class="purple-text">--% (Enter above)</span>
                            </div>
                            <div class="summary-row">
                                <span>Duration:</span>
                                <span><?php echo $req['duration_months']; ?> months</span>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit-offer">Submit Offer</button>
                    </form>
                </div>
            </div>

            <div class="RightCol">
                
                <div class="SidebarWallet">
                    <div class="wallet-header">
                        <i class="fa-solid fa-dollar-sign"></i> <span>Your Balance</span>
                    </div>
                    <h1>৳<?php echo number_format($user_balance); ?></h1>
                    
                    <div class="sw-row">
                        <span>Available:</span>
                        <span>৳<?php echo number_format($user_balance); ?></span>
                    </div>
                    
                    <div class="divider-white"></div>
                    
                    <div class="sw-row highlight">
                        <span>After This Offer:</span>
                        <span>৳<?php echo number_format($user_balance - $req['amount']); ?></span>
                    </div>
                    
                    <div class="safe-check">
                        <?php if($user_balance >= $req['amount']): ?>
                            <i class="fa-regular fa-circle-check"></i> Sufficient funds available
                        <?php else: ?>
                            <i class="fa-solid fa-circle-xmark"></i> Insufficient funds
                        <?php endif; ?>
                    </div>
                </div>

                <div class="InfoCard">
                    <h3><i class="fa-regular fa-clock"></i> How It Works</h3>
                    <ul class="steps-list">
                        <li>
                            <span class="step-num">1</span>
                            <div>
                                <strong>Submit Your Offer</strong>
                                <p>Funds locked until accepted/rejected</p>
                            </div>
                        </li>
                        <li>
                            <span class="step-num">2</span>
                            <div>
                                <strong>Borrower Reviews</strong>
                                <p>They see all offers and pick the best</p>
                            </div>
                        </li>
                        <li>
                            <span class="step-num">3</span>
                            <div>
                                <strong>If Accepted</strong>
                                <p>Loan begins, earn interest monthly</p>
                            </div>
                        </li>
                        <li>
                            <span class="step-num">4</span>
                            <div>
                                <strong>If Rejected</strong>
                                <p>Funds returned to your wallet</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="InfoCard yellow-bg">
                    <h3><i class="fa-solid fa-shield-halved"></i> Protected Investment</h3>
                    <ul class="simple-list">
                        <li>Funds held securely in escrow</li>
                        <li>Automatic monthly payments</li>
                        <li>Late payment penalties apply</li>
                        <li>Dispute resolution available</li>
                    </ul>
                </div>

            </div>

        </div>
    </div>
</body>
</html>
