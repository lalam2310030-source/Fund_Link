<?php
session_start();
include '../db_connect.php';

// Fetch Stats
$stats = $conn->query("SELECT 
    (SELECT COUNT(*) FROM crowdfunding_campaigns) as total_campaigns,
    (SELECT SUM(collected_amount) FROM crowdfunding_campaigns) as total_raised,
    (SELECT COUNT(DISTINCT donor_id) FROM crowdfunding_donations) as total_backers,
    (SELECT COUNT(*) FROM crowdfunding_campaigns WHERE collected_amount >= goal_amount) as successful_campaigns
")->fetch_assoc();

$success_rate = $stats['total_campaigns'] > 0 ? round(($stats['successful_campaigns'] / $stats['total_campaigns']) * 100) : 0;

// Fetch Top 2 Campaigns
$top_campaigns = $conn->query("SELECT c.*, COUNT(d.id) as backer_count 
    FROM crowdfunding_campaigns c 
    LEFT JOIN crowdfunding_donations d ON c.id = d.campaign_id 
    WHERE c.status = 'active' 
    GROUP BY c.id 
    ORDER BY c.collected_amount DESC LIMIT 2");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crowdfunding</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <div class="MainWrapper">
        <div class="PageHeader">
            <p><strong>Crowdfunding ❤️</strong></p>
            <h5>Support community projects</h5>
            <div style="margin-top: 15px;">
                <a href="CreateNewCampaign.php" style="background-color: #8b5cf6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; margin-right: 10px;">+ Start Campaign</a>
                <a href="SupportCampaign.php" style="background-color: #10b981; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">Browse All</a>
            </div>
        </div>

        <div class="ImpactSummary dflex">
            <div class="SummaryCard total-campaigns">
                <p>Campaigns</p>
                <h2><?php echo $stats['total_campaigns']; ?></h2>
            </div>
            <div class="SummaryCard total-raised">
                <p>Total Raised</p>
                <h2>৳<?php echo number_format($stats['total_raised'] ?? 0); ?></h2>
            </div>
            <div class="SummaryCard total-backers">
                <p>Backers</p>
                <h2><?php echo $stats['total_backers']; ?></h2>
            </div>
            <div class="SummaryCard success-rate">
                <p>Success</p>
                <h2><?php echo $success_rate; ?>%</h2>
            </div>
        </div>

        <div class="CampaignGrid">
            <?php if ($top_campaigns->num_rows > 0): ?>
                <?php while($row = $top_campaigns->fetch_assoc()): 
                    $percent = ($row['collected_amount'] / $row['goal_amount']) * 100;
                    $percent = min(100, $percent);
                    $days_left = ceil((strtotime($row['deadline']) - time()) / (60 * 60 * 24));
                    if ($days_left < 0) $days_left = 0;
                ?>
                <div class="CampaignCard">
                    <div class="CategoryHeader bg-blue">
                        <i class="fa-solid fa-droplet"></i>
                        <h4>Active Campaign</h4>
                    </div>
                    <div class="CampaignBody">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p class="CampaignDescription"><?php echo htmlspecialchars(substr($row['description'], 0, 100)) . '...'; ?></p>

                        <div class="FundStatus dflex j-between">
                            <span class="Percentage"><?php echo number_format($percent, 1); ?>% Funded</span>
                            <span class="GoalAmount">৳<?php echo number_format($row['collected_amount']); ?> of ৳<?php echo number_format($row['goal_amount']); ?></span>
                        </div>

                        <div class="ProgressBar">
                            <div class="BarFill bg-blue" style="width: <?php echo $percent; ?>%;"></div>
                        </div>

                        <div class="CampaignFooter dflex j-between">
                            <div class="FooterInfo"><i class="fa-solid fa-users"></i> <?php echo $row['backer_count']; ?> backers</div>
                            <div class="FooterInfo"><i class="fa-regular fa-calendar"></i> <?php echo $days_left; ?> days left</div>
                        </div>

                        <button class="btn-support support-blue">
                            <i class="fa-regular fa-heart"></i><a href="SupportCampaign.php" style="color: inherit; text-decoration: none;">Support Campaign</a>
                        </button>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align: center; width: 100%;">No active campaigns yet. Be the first to start one!</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>