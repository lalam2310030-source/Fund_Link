<?php
session_start();
include '../db_connect.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    
    if (empty($full_name) || empty($email) || empty($username) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Username or Email already exists.";
        } else {
           
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, username, password, role) VALUES (?, ?, ?, ?, 'user')");
            $stmt->bind_param("ssss", $full_name, $email, $username, $hashed_password);

            if ($stmt->execute()) {
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Error registering user: " . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - FundLink</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>

<body>
    <div class="dflex Ui">
        <div class="Left">
            <div>
                <h1><i class="fa-solid fa-handshake"></i>FundLink</h1>
                <p>Peer-to-peer Lending Platform</p>

            </div>
            <div>
                <ul>
                    <li><i class="fa-solid fa-code-pull-request"></i><strong>Request Loans</strong></li>
                    <p>Get competitive offers</p>
                    <li><i class="fa-solid fa-hand-holding-dollar"></i><strong>Invest & Earn</strong></li>
                    <p>Lend money and earn profit</p>
                    <li><i class="fa-solid fa-group-arrows-rotate"></i><strong>Community Support</strong></li>
                    <p>Help each other grow</p>
                </ul>
            </div>
        </div>
        <div class="Right">
            <h3>Create Account</h3>
            <p>Join our community today</p>
            
            <?php if($error): ?>
                <p style="color: red; font-size: 0.9rem; margin-bottom: 10px;"><?php echo $error; ?></p>
            <?php endif; ?>
            
            <?php if($success): ?>
                <p style="color: green; font-size: 0.9rem; margin-bottom: 10px;"><?php echo $success; ?></p>
            <?php endif; ?>

            <form method="POST" action="">
                <label>Full Name</label><br>
                <input type="text" name="full_name" required value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>"><br>
                
                <label>Email</label><br>
                <input type="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"><br>

                <label>Username</label><br>
                <input type="text" name="username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"><br>
                
                <label>Password</label><br>
                <input type="password" name="password" required><br>
                
                <label>Confirm Password</label><br>
                <input type="password" name="confirm_password" required><br>

                <button type="submit">Register</button><br>
            </form>
            <p>Already have an account? <span><a href="index.php" style="color: #6366f1; text-decoration: none; font-weight: bold;">Login</a></span></p>

        </div>
    </div>

</body>

</html>
