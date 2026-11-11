<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login_&_Register/index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$requests = [];
$sql = "SELECT lr.*, u.full_name, u.created_at as member_since, 
        (SELECT COUNT(*) FROM loan_offers WHERE loan_request_id = lr.id) as offer_count,
        (SELECT MIN(interest_rate) FROM loan_offers WHERE loan_request_id = lr.id) as best_rate
        FROM loan_requests lr 
        JOIN users u ON lr.user_id = u.id 
        WHERE lr.status = 'open' AND lr.user_id != $user_id
        ORDER BY lr.created_at DESC";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $requests[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Marketplace</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <div class="MainWrapper">
        <div class="TopNav" style="margin-bottom: 20px;">
            <a href="../Dashboard/index.php" style="text-decoration: none; color: #333;"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
        </div>
        <div class="Top">
            <p class="header-sub">LOAN MARKETPLACE</p>
            <h1 class="header-main">Browse and invest in loan requests</h1>
        </div>

        <div class="SearchSection">
            <div class="SearchBox">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Search loan requests...">
            </div>
        </div>

        <div class="LoanGrid">
            <?php if(count($requests) > 0): ?>
                <?php foreach($requests as $req): ?>
                    <div class="LoanCard theme-purple">
                        <div class="top-accent"></div>

                        <div class="CardHeader">
                            <div class="user-info">
                                <div class="UserIcon bg-purple"><?php echo strtoupper(substr($req['full_name'], 0, 1)); ?></div>
                                <div class="user-details">
                                    <p class="user-name"><?php echo htmlspecialchars($req['full_name']); ?></p>
                                    <span class="member-since"><i class="fa-solid fa-star"></i> Member since <?php echo date('Y', strtotime($req['member_since'])); ?></span>
                                </div>
                            </div>
                            <div class="Badge badge-green"><?php echo strtoupper($req['status']); ?></div>
                        </div>

                        <h3 class="loan-title"><?php echo htmlspecialchars($req['title']); ?></h3>
                        <p class="description"><?php echo htmlspecialchars($req['description']); ?></p>

                        <div class="LoanStats">
                            <div class="stat-box bg-purple-soft">
                                <p class="stat-label">AMOUNT</p>
                                <h4 class="stat-value">৳<?php echo number_format($req['amount']); ?></h4>
                            </div>
                            <div class="stat-box bg-purple-soft">
                                <p class="stat-label">DURATION</p>
                                <h4 class="stat-value"><?php echo $req['duration_months']; ?> months</h4>
                            </div>
                            <div class="stat-box bg-purple-soft">
                                <p class="stat-label">MAX RATE</p>
                                <h4 class="stat-value"><?php echo $req['max_interest_rate']; ?>%</h4>
                            </div>
                        </div>

                        <div class="OfferBar offer-purple">
                            <p><i class="fa-solid fa-arrow-trend-up"></i> <?php echo $req['offer_count']; ?> offers</p>
                            <p class="best"><?php echo $req['best_rate'] ? 'Best: ' . $req['best_rate'] . '%' : 'No offers yet'; ?></p>
                        </div>

                        <button class="btn-make-offer btn-purple">
                            <a href="Make_an_Offer.php?id=<?php echo $req['id']; ?>">Make an Offer</a>
                            <div class="btn-overlay"></div>
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No open loan requests found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>