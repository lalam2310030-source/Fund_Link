<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login_&_Register/index.php");
    exit();
}

$success_msg = "";
$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $goal_amount = $_POST['goal_amount'];
    $duration_days = $_POST['duration'];
    $user_id = $_SESSION['user_id'];
    
    // Calculate deadline
    $deadline = date('Y-m-d', strtotime("+$duration_days days"));
    
    $sql = "INSERT INTO crowdfunding_campaigns (user_id, title, description, goal_amount, deadline, status) 
            VALUES (?, ?, ?, ?, ?, 'active')";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issds", $user_id, $title, $description, $goal_amount, $deadline);
    
    if ($stmt->execute()) {
        $success_msg = "Campaign created successfully!";
    } else {
        $error_msg = "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Campaign</title>
    <link rel="stylesheet" href="CreateNewCampaign.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

    <div class="MainWrapper">

        <div class="Top">
            <small class="sub-text">CROWDFUNDING</small>
            <h1>Create a new campaign</h1>
            <p>Share your project with the community and raise funds</p>
            
            <?php if ($success_msg): ?>
                <div style="background-color: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-top: 20px;">
                    <?php echo $success_msg; ?> <a href="SupportCampaign.php" style="color: #059669; font-weight: bold;">View Campaigns</a>
                </div>
            <?php endif; ?>
            
            <?php if ($error_msg): ?>
                <div style="background-color: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-top: 20px;">
                    <?php echo $error_msg; ?>
                </div>
            <?php endif; ?>
        </div>

        <form method="POST" class="ContentArea dflex">

            <div class="LeftSide">
                
                <div class="SectionCard">
                    <h2>Campaign Details</h2>

                    <div class="InputGroup">
                        <label>Campaign Title</label>
                        <div class="InputWrapper">
                            <i class="fa-regular fa-file-lines icon-purple"></i>
                            <input type="text" name="title" required placeholder="e.g., Community Water Initiative">
                        </div>
                        <small class="helper">Choose a clear, descriptive title</small>
                    </div>

                    <div class="InputGroup">
                        <label>Campaign Description</label>
                        <textarea name="description" required placeholder="Describe your project in detail. Explain what you're raising funds for, why it's important, and how it will benefit the community..."></textarea>
                        <small class="helper">Be specific and compelling to attract supporters</small>
                    </div>

                    <div class="InputGroup">
                        <label>Campaign Category</label>
                        <div class="InputWrapper">
                            <select name="category">
                                <option>Select a category</option>
                                <option>Education</option>
                                <option>Health</option>
                                <option>Community Infrastructure</option>
                                <option>Emergency Relief</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="SectionCard">
                    <h2>Funding Details</h2>

                    <div class="Row dflex">
                        <div class="InputGroup flex-1">
                            <label>Funding Goal (৳)</label>
                            <div class="InputWrapper">
                                <i class="fa-solid fa-bangladeshi-taka-sign icon-purple"></i>
                                <input type="number" name="goal_amount" required placeholder="250000">
                            </div>
                            <small class="helper">Minimum: ৳10,000</small>
                        </div>
                        <div class="InputGroup flex-1">
                            <label>Campaign Duration</label>
                            <div class="InputWrapper">
                                <i class="fa-regular fa-calendar icon-purple"></i>
                                <select name="duration" required>
                                    <option value="">Select duration</option>
                                    <option value="30">30 Days</option>
                                    <option value="60">60 Days</option>
                                    <option value="90">90 Days</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Submit Campaign</button>

            </div>

            <div class="RightSide">

                <div class="SideCard bg-purple-gradient text-white">
                    <i class="fa-regular fa-lightbulb big-icon"></i>
                    <h3>Campaign Tips</h3>
                    
                    <ul class="TipsList">
                        <li>
                            <i class="fa-regular fa-circle-check"></i>
                            <div>
                                <strong>Be Clear & Specific</strong>
                                <p>Explain exactly what you need and why</p>
                            </div>
                        </li>
                        <li>
                            <i class="fa-regular fa-circle-check"></i>
                            <div>
                                <strong>Set Realistic Goals</strong>
                                <p>Research similar campaigns for guidance</p>
                            </div>
                        </li>
                        <li>
                            <i class="fa-regular fa-circle-check"></i>
                            <div>
                                <strong>Use Quality Images</strong>
                                <p>Visual appeal attracts more backers</p>
                            </div>
                        </li>
                        <li>
                            <i class="fa-regular fa-circle-check"></i>
                            <div>
                                <strong>Tell Your Story</strong>
                                <p>Personal stories create connections</p>
                            </div>
                        </li>
                        <li>
                            <i class="fa-regular fa-circle-check"></i>
                            <div>
                                <strong>Share Widely</strong>
                                <p>Promote on social media and networks</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="SideCard bg-white">
                    <div class="StatHeader">
                        <i class="fa-solid fa-bullseye icon-purple"></i> 
                        <h3>Success Stats</h3>
                    </div>
                    
                    <div class="StatRow dflex j-between">
                        <span>Average Goal:</span>
                        <strong>৳175,000</strong>
                    </div>
                    <div class="StatRow dflex j-between">
                        <span>Success Rate:</span>
                        <strong class="text-green">67%</strong>
                    </div>
                    <div class="StatRow dflex j-between">
                        <span>Avg. Backers:</span>
                        <strong>124</strong>
                    </div>
                    <div class="StatRow dflex j-between">
                        <span>Avg. Duration:</span>
                        <strong>30 days</strong>
                    </div>
                </div>

                <div class="SideCard bg-yellow-soft border-yellow">
                    <div class="StatHeader">
                        <i class="fa-solid fa-circle-exclamation icon-orange"></i>
                        <h3 class="text-brown">Important Guidelines</h3>
                    </div>
                    <ul class="GuideList">
                        <li>Campaigns must be for legitimate community projects</li>
                        <li>All funds must be used as described</li>
                        <li>Regular updates are required</li>
                        <li>Platform fee: 5% of total raised</li>
                    </ul>
                </div>

            </div>
        </div>

    </div>
</body>
</html>