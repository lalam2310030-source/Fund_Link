<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login_&_Register/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FundLink Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <div class="Dashboard">

        <div class="Left">
            <div>
                <h1><i class="fa-solid fa-handshake"></i>FundLink</h1>
                <p>Peer-to-peer Lending Platform</p>
            </div>
            <div class="DashboardList">
                <ul>
                    <li><a href="../Dashboard/index.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
                    <li><a href="../Wallet Management/"><i class="fa-solid fa-wallet"></i> Wallet Management</a></li>
                    <li><a href="../Loan Marketplace/"><i class="fa-solid fa-shop"></i> Loan Marketplace</a></li>
                    <li><a href="../Create Loan Request/"><i class="fa-solid fa-plus"></i> Create Loan Request</a></li>
                    <li><a href="../Loan Request Details/"><i class="fa-solid fa-file-invoice"></i> Loan Details</a>
                    </li>
                    <li><a href="../My Loans/"><i class="fa-solid fa-hand-holding-dollar"></i> My Loans</a></li>
                    <li><a href="../My Investments/"><i class="fa-solid fa-chart-line"></i> My Investment</a></li>
                    <li><a href="../Crowdfunding Campaigns/"><i class="fa-solid fa-users"></i> Crowdfunding
                            Campaigns</a></li>
                    <li><a href="../About us/aboutus.php"><i class="fa-solid fa-circle-info"></i> About Us</a></li>
                </ul>
            </div>
        </div>

        <div class="Main">
            <h2>Welcome Back!</h2>
            <div class="content-card">
                <p>Select an option from the menu to get started.</p>
            </div>
        </div>

        <div class="RightSide">
            <div class="ProfileCard">
                <div class="ProfileIcon">
                    <img src="Rafat.jpg" alt="user">
                </div>
                <h3><?php echo htmlspecialchars($_SESSION['full_name']); ?></h3>
                <p>User</p>
                <a href="../logout.php"><button class="logout-btn">Log Out</button></a>
            </div>
        </div>
    </div>
</body>

</html>
