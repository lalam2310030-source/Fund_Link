<?php
session_start();
include '../db_connect.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

   
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        
        $stmt = $conn->prepare("SELECT id, username, full_name, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            
            if (password_verify($password, $row['password']) || $password === $row['password']) { 
                session_regenerate_id(true);
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['full_name'] = $row['full_name'];
                $_SESSION['role'] = $row['role'];
                header("Location: " . ($row['role'] === 'admin' ? '../Admin/index.php' : '../Dashboard/index.php'));
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "User not found.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login_&_Register</title>
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
            <h3>Welcome Back</h3>
            <p>Login to your acount</p>
            <?php if($error): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <label>Username</label><br>
                <input type="text" name="username" required><br>
                <label>Password</label><br>
                <input type="password" name="password" required><br>

                <button type="submit">Login</button><br>
            </form>
            <p>Don't have an account? <span><a href="register.php" style="color: #6366f1; text-decoration: none; font-weight: bold;">Create One</a></span></p>

        </div>
    </div>

</body>

</html>
