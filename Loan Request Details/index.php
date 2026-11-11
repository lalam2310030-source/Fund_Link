<?php
session_start();
include '../db_connect.php';


if (!isset($_SESSION['user_id'])) {
    
    $_SESSION['user_id'] = 1; 
}

$user_id = $_SESSION['user_id'];
$loan_request_id = isset($_GET['id']) ? intval($_GET['id']) : 1;


$sql = "SELECT lr.*, u.full_name, u.username 
        FROM loan_requests lr 
        JOIN users u ON lr.user_id = u.id 
        WHERE lr.id = $loan_request_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    
    $sql_fallback = "SELECT lr.*, u.full_name, u.username 
                     FROM loan_requests lr 
                     JOIN users u ON lr.user_id = u.id 
                     WHERE lr.user_id = $user_id LIMIT 1";
    $result = $conn->query($sql_fallback);
    
    if ($result->num_rows == 0) {
        die("No loan request found. Please create one first.");
    }
}

$request = $result->fetch_assoc();
$loan_request_id = $request['id'];


$offers_sql = "SELECT lo.*, u.full_name as lender_name, u.username as lender_username 
               FROM loan_offers lo 
               JOIN users u ON lo.lender_user_id = u.id 
               WHERE lo.loan_request_id = $loan_request_id 
               ORDER BY lo.interest_rate ASC";
$offers_result = $conn->query($offers_sql);

$offers = [];
if ($offers_result->num_rows > 0) {
    while($row = $offers_result->fetch_assoc()) {
        $offers[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Request Details</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

<div class="container">
    
    <main class="main-content">
        
        <div style="margin-bottom: 20px;">
            <h2><?php echo htmlspecialchars($request['title']); ?></h2>
            <div class="loan-id">#LR-<?php echo date('Y', strtotime($request['created_at'])); ?>-<?php echo str_pad($request['id'], 3, '0', STR_PAD_LEFT); ?></div>
        </div>

        <div class="card">
            <h4 style="margin-bottom: 16px; font-weight: 500;">Borrower</h4>

            <div class="borrower-section">
                <div class="avatar">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="borrower-info">
                    <h3><?php echo htmlspecialchars($request['full_name']); ?></h3>
                    <div>
                        <span class="rating">★ 4.8/5</span>
                        <span style="color:#9ca3af; margin:0 5px;">•</span>
                        <span style="color:#4b5563;">Member</span>
                    </div>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-box amount">৳<?php echo number_format($request['amount']); ?></div>
                <div class="stat-box duration"><?php echo $request['duration_months']; ?> mo</div>
                <div class="stat-box rate"><?php echo $request['max_interest_rate']; ?>%</div>
                <div class="stat-box category"><?php echo htmlspecialchars($request['category']); ?></div>
            </div>

            <div class="purpose-section">
                <h4>Loan Purpose</h4>
                <p><?php echo nl2br(htmlspecialchars($request['description'])); ?></p>
            </div>
        </div>

        <div>
            <h3 class="offers-title">Offers Received (<?php echo count($offers); ?>)</h3>

            <?php if (count($offers) > 0): ?>
                <?php foreach($offers as $index => $offer): ?>
                    <?php 
                        
                        $rate = $offer['interest_rate'] / 100;
                        $monthly_rate = $rate / 12;
                        $months = $request['duration_months'];
                        $amount = $request['amount'];
                        
                        
                        if ($rate > 0) {
                            $emi = $amount * $monthly_rate * pow(1 + $monthly_rate, $months) / (pow(1 + $monthly_rate, $months) - 1);
                        } else {
                            $emi = $amount / $months;
                        }
                        $total_repayment = $emi * $months;
                        
                        $is_best = ($index === 0);
                    ?>
                    <div class="offer-card <?php echo $is_best ? 'best-offer' : ''; ?>">
                        <div class="offer-header">
                            <div class="borrower-section" style="margin-bottom: 0;">
                                <div class="avatar" style="background-color: <?php echo $is_best ? '#10b981' : '#8b5cf6'; ?>; width: 45px; height: 45px; font-size: 1.2rem;">
                                    <?php echo strtoupper(substr($offer['lender_name'], 0, 1)); ?>
                                </div>
                                <div class="borrower-info">
                                    <h3 style="font-size: 1rem; margin-bottom: 2px;"><?php echo htmlspecialchars($offer['lender_name']); ?></h3>
                                    <div style="font-size: 0.85rem; color: #4b5563;">
                                        Lender
                                    </div>
                                </div>
                            </div>
                            <?php if ($request['status'] == 'open' && $offer['status'] == 'pending'): ?>
                            <div class="offer-badge" style="<?php echo $is_best ? '' : 'background-color: #8b5cf6;'; ?>">
                                <span>🏷️</span> <?php echo $offer['interest_rate']; ?>% Rate
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="offer-details">
                            <div class="detail-item">
                                <span>Rate</span>
                                <strong class="highlight" style="color: <?php echo $is_best ? '#059669' : '#7c3aed'; ?>;"><?php echo $offer['interest_rate']; ?>%</strong>
                            </div>
                            <div class="detail-item">
                                <span>Monthly</span>
                                <strong>৳<?php echo number_format($emi, 2); ?></strong>
                            </div>
                            <div class="detail-item">
                                <span>Total</span>
                                <strong>৳<?php echo number_format($total_repayment, 2); ?></strong>
                            </div>
                        </div>

                        <?php if ($request['status'] == 'open' && $offer['status'] == 'pending'): ?>
                        <form action="accept_offer.php" method="POST">
                            <input type="hidden" name="offer_id" value="<?php echo $offer['id']; ?>">
                            <input type="hidden" name="loan_request_id" value="<?php echo $request['id']; ?>">
                            <button type="submit" class="btn-full" style="width: 100%; padding: 12px; background: <?php echo $is_best ? '#10b981' : '#7c3aed'; ?>; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                                <?php echo $is_best ? 'Accept Best Offer' : 'Accept This Offer'; ?>
                            </button>
                        </form>
                        <?php elseif ($offer['status'] == 'accepted'): ?>
                            <button class="btn btn-success" disabled style="width: 100%; padding: 12px; background: #10b981; color: white; border: none; border-radius: 8px;">Accepted</button>
                        <?php elseif ($offer['status'] == 'rejected'): ?>
                            <button class="btn btn-danger" disabled style="width: 100%; padding: 12px; background: #ef4444; color: white; border: none; border-radius: 8px;">Rejected</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="card">
                    <p style="text-align: center; color: #666;">No offers received yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <aside class="sidebar">
        <div style="text-align: right; margin-bottom: 24px; height: 50px; padding-top: 10px;">
            <span class="status-badge"><?php echo ucfirst($request['status']); ?></span>
        </div>
        
        
        <?php if ($user_id != $request['user_id']): ?>
        <div class="card make-offer-card">
            <h3>Make Your Offer</h3>
            <form action="submit_offer.php" method="POST">
                <input type="hidden" name="loan_request_id" value="<?php echo $request['id']; ?>">
                <div class="input-group">
                    <label>Interest Rate (%)</label>
                    <input type="number" name="interest_rate" step="0.1" class="input-field" placeholder="e.g. 9.0" required>
                </div>
                <button type="submit" class="btn btn-white">Submit Offer</button>
            </form>
        </div>
        <?php endif; ?>

        <div class="card">
            <h3 style="margin-bottom: 20px; font-size: 1.1rem;">Activity</h3>
            <ul class="activity-list">
                <li class="activity-item dot-gray">
                    <div class="activity-text">Request Published</div>
                    <div class="activity-time"><?php echo date('M d, H:i', strtotime($request['created_at'])); ?></div>
                </li>
            </ul>
        </div>
    </aside>

</div>

</body>
</html>
