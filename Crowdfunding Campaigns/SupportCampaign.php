<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login_&_Register/index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$msg = "";

// Handle Donation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['donate'])) {
    $campaign_id = $_POST['campaign_id'];
    $amount = $_POST['amount'];

    if ($amount > 0) {
        $conn->begin_transaction();
        try {
            // Check balance
            $stmt = $conn->prepare("SELECT balance FROM users WHERE id = ? FOR UPDATE");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $res = $stmt->get_result();
            $user = $res->fetch_assoc();

            if ($user['balance'] >= $amount) {
                // Deduct from user
                $stmt = $conn->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
                $stmt->bind_param("di", $amount, $user_id);
                $stmt->execute();

                // Add to campaign
                $stmt = $conn->prepare("UPDATE crowdfunding_campaigns SET collected_amount = collected_amount + ? WHERE id = ?");
                $stmt->bind_param("di", $amount, $campaign_id);
                $stmt->execute();

                // Record donation
                $stmt = $conn->prepare("INSERT INTO crowdfunding_donations (campaign_id, donor_id, amount) VALUES (?, ?, ?)");
                $stmt->bind_param("iid", $campaign_id, $user_id, $amount);
                $stmt->execute();

                // Record transaction
                $stmt = $conn->prepare("INSERT INTO transactions (user_id, type, amount, description) VALUES (?, 'withdrawal', ?, ?)");
                $desc = "Donation to Campaign ID: " . $campaign_id;
                $stmt->bind_param("ids", $user_id, $amount, $desc);
                $stmt->execute();

                $conn->commit();
                $msg = "<div style='background-color: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px;'>Donation of ৳$amount successful!</div>";
            } else {
                throw new Exception("Insufficient balance.");
            }
        } catch (Exception $e) {
            $conn->rollback();
            $msg = "<div style='background-color: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px;'>Error: " . $e->getMessage() . "</div>";
        }
    }
}

// Fetch Stats
$total_campaigns_res = $conn->query("SELECT COUNT(*) as count FROM crowdfunding_campaigns WHERE status = 'active'");
$total_campaigns = $total_campaigns_res->fetch_assoc()['count'];

$total_raised_res = $conn->query("SELECT SUM(collected_amount) as total FROM crowdfunding_campaigns");
$total_raised = $total_raised_res->fetch_assoc()['total'] ?? 0;

// Fetch Campaigns
$sql = "SELECT c.*, COUNT(d.id) as backer_count 
        FROM crowdfunding_campaigns c 
        LEFT JOIN crowdfunding_donations d ON c.id = d.campaign_id 
        WHERE c.status = 'active' 
        GROUP BY c.id ORDER BY c.created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Campaigns</title>
    <link rel="stylesheet" href="SupportCampaign.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); }
        .modal-content { background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 300px; border-radius: 8px; text-align: center; }
        .close { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
        .close:hover, .close:focus { color: black; text-decoration: none; cursor: pointer; }
        .btn-donate { background-color: #8b5cf6; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-top: 10px; }
    </style>
</head>
<body>

    <div class="MainWrapper">

        <div class="TopSection">
            <div class="TopHeader">
                <p class="sub-title">CROWDFUNDING CAMPAIGNS</p>
                <h1>Support community projects</h1>
                <a href="CreateNewCampaign.php" style="display: inline-block; margin-top: 10px; color: #8b5cf6; text-decoration: none; font-weight: bold;">+ Start a Campaign</a>
            </div>

            <div class="TopControls dflex j-between">
                <div class="SearchPart">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Search campaigns...">
                </div>

                <div class="TopRightStats dflex">
                    <div class="StatItem">
                        <small>ACTIVE CAMPAIGNS</small>
                        <h3><?php echo $total_campaigns; ?></h3>
                    </div>
                    <div class="StatItem">
                        <small>TOTAL RAISED</small>
                        <h3 class="text-purple">৳<?php echo number_format($total_raised); ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="MiddleSection">
            <?php echo $msg; ?>
            
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): 
                    $percent = ($row['collected_amount'] / $row['goal_amount']) * 100;
                    $percent = min(100, $percent); // Cap at 100 for visual bar
                    $days_left = ceil((strtotime($row['deadline']) - time()) / (60 * 60 * 24));
                    if ($days_left < 0) $days_left = 0;
                ?>
                <div class="CampaignCard">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p class="desc"><?php echo htmlspecialchars($row['description']); ?></p>
                    
                    <div class="ProgressInfo dflex j-between">
                        <strong><?php echo number_format($percent, 1); ?>% Funded</strong>
                        <span>৳<?php echo number_format($row['collected_amount']); ?> of ৳<?php echo number_format($row['goal_amount']); ?></span>
                    </div>
                    <div class="ProgressBarBase">
                        <div class="ProgressFill bg-purple" style="width: <?php echo $percent; ?>%;"></div>
                    </div>

                    <div class="MetaInfo dflex j-between">
                        <span><i class="fa-solid fa-user-group"></i> <?php echo $row['backer_count']; ?> backers</span>
                        <span><i class="fa-regular fa-clock"></i> <?php echo $days_left; ?> days left</span>
                    </div>

                    <button class="btn-support bg-purple" onclick="openModal(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['title']); ?>')">
                        <i class="fa-regular fa-heart"></i> Support Campaign
                    </button>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No active campaigns found. <a href="CreateNewCampaign.php">Be the first to create one!</a></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- The Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3 id="modalTitle">Support Campaign</h3>
            <form method="POST">
                <input type="hidden" id="campaign_id" name="campaign_id">
                <div style="margin: 15px 0;">
                    <label>Amount (৳):</label>
                    <input type="number" name="amount" required min="1" style="padding: 5px; width: 100px;">
                </div>
                <button type="submit" name="donate" class="btn-donate">Donate</button>
            </form>
        </div>
    </div>

    <script>
        var modal = document.getElementById("myModal");
        function openModal(id, title) {
            document.getElementById('campaign_id').value = id;
            document.getElementById('modalTitle').innerText = "Support: " + title;
            modal.style.display = "block";
        }
        function closeModal() {
            modal.style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

</body>
</html>
                <button class="btn-support bg-purple">
                    <i class="fa-regular fa-heart"></i> Support Campaign
                </button>
            </div>

            <div class="CampaignCard">
                <h3>School Library Books</h3>
                <p class="desc">Building a library with 1000+ books for primary school students</p>
                
                <div class="ProgressInfo dflex j-between">
                    <strong>45% Funded</strong>
                    <span>৳45,000 of ৳100,000</span>
                </div>
                <div class="ProgressBarBase">
                    <div class="ProgressFill bg-orange" style="width: 45%;"></div>
                </div>

                <div class="MetaInfo dflex j-between">
                    <span><i class="fa-solid fa-user-group"></i> 92 backers</span>
                    <span><i class="fa-regular fa-clock"></i> 20 days left</span>
                </div>

                <button class="btn-support bg-orange">
                    <i class="fa-regular fa-heart"></i> Support Campaign
                </button>
            </div>

        </div>

        <div class="LowerSection bg-gradient">
            <div class="BannerContent">
                <i class="fa-solid fa-arrow-trend-up icon-large"></i>
                <h2>Have a Project in Mind?</h2>
                <p>Start your own crowdfunding campaign and get support from our community</p>
                <button class="btn-create">
                    <i class="fa-solid fa-plus"></i> Create New Campaign
                </button>
            </div>
        </div>

    </div>
</body>
</html>