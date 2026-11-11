<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login_&_Register/index.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $duration = $_POST['duration'];
    $interest_rate = $_POST['interest_rate'];
    $category = $_POST['category'];
    $user_id = $_SESSION['user_id'];

    if ($amount > 0 && $duration > 0 && $interest_rate > 0) {
        $stmt = $conn->prepare("INSERT INTO loan_requests (user_id, title, description, amount, duration_months, max_interest_rate, category, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'open')");
        $stmt->bind_param("issdids", $user_id, $title, $description, $amount, $duration, $interest_rate, $category);
        
        if ($stmt->execute()) {
            $message = "Loan request submitted successfully!";
        } else {
            $message = "Error: " . $conn->error;
        }
    } else {
        $message = "Please fill all fields correctly.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CreateLoanRequest</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="MainWrapper">
        <div class="TopNav" style="margin-bottom: 20px;">
            <a href="../Dashboard/index.php" style="text-decoration: none; color: #333;"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
        </div>
        <div class="Top">
            <p><strong>Request a Loan</strong></p>
            <h5>Get competitive offers from lenders</h5>
        </div>

        <?php if($message) echo "<p style='color: " . (strpos($message, 'Error') !== false ? 'red' : 'green') . "; font-weight: bold; background: #d4edda; padding: 10px; border-radius: 5px; margin-bottom: 20px;'>$message</p>"; ?>

        <div class="LayoutGrid dflex">
            
            <div class="FormCard">
                <form method="POST">
                <div class="InputGroup">
                    <label>Loan Title</label>
                    <input type="text" name="title" placeholder="e.g. Business Expansion Loan" required>
                </div>

                <div class="InputGroup">
                    <label>Description</label>
                    <textarea name="description" placeholder="I need funds to expand my retail shop business..." required></textarea>
                </div>

                <div class="dflex">
                    <div class="InputGroup flex-1">
                        <label>Amount (৳)</label>
                        <input type="number" name="amount" placeholder="50000" required>
                    </div>
                    <div class="InputGroup flex-1">
                        <label>Duration (Months)</label>
                        <select name="duration">
                            <option value="6">6 months</option>
                            <option value="12" selected>12 months</option>
                            <option value="18">18 months</option>
                            <option value="24">24 months</option>
                        </select>
                    </div>
                </div>

                <div class="InputGroup">
                    <label>Maximum Interest Rate (%)</label>
                    <input type="number" name="interest_rate" placeholder="15" step="0.1" required>
                    <span class="input-hint">Lenders can offer lower rates</span>
                </div>

                <div class="InputGroup">
                    <label>Category</label>
                    <select name="category">
                        <option value="Business">Business</option>
                        <option value="Education">Education</option>
                        <option value="Medical">Medical</option>
                        <option value="Personal">Personal</option>
                    </select>
                </div>

                <button type="submit" class="btn-submit">Submit Loan Request</button>
                </form>
            </div>

            <div class="Sidebar">
                <div class="SummaryBox bg-purple-gradient">
                    <p class="title">Loan Summary</p>
                    <div class="summary-item">
                        <span>Amount</span>
                        <h4>৳50,000</h4>
                    </div>
                    <div class="summary-item">
                        <span>Duration</span>
                        <h4>12 months</h4>
                    </div>
                    <div class="summary-item">
                        <span>Max Rate</span>
                        <h4>15%</h4>
                    </div>
                    <hr>
                    <div class="summary-item">
                        <span>Estimated Monthly</span>
                        <h4>~৳4,500</h4>
                    </div>
                </div>

                <div class="HowItWorks">
                    <p class="title"><i class="fa-regular fa-lightbulb"></i> How It Works</p>
                    <div class="step dflex align-center">
                        <div class="step-num">1</div>
                        <div>
                            <p><strong>Submit Request</strong></p>
                            <span>Goes live on marketplace</span>
                        </div>
                    </div>
                    <div class="step dflex align-center">
                        <div class="step-num step-purple">2</div>
                        <div>
                            <p><strong>Receive Offers</strong></p>
                            <span>Lenders compete</span>
                        </div>
                    </div>
                    <div class="step dflex align-center">
                        <div class="step-num step-green">3</div>
                        <div>
                            <p><strong>Accept Best</strong></p>
                            <span>Get funds instantly</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>