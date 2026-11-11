<?php
include 'db_connect.php';

// Add role column to users table if it doesn't exist
$sql = "SHOW COLUMNS FROM users LIKE 'role'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    $alter_sql = "ALTER TABLE users ADD COLUMN role ENUM('user', 'admin') DEFAULT 'user'";
    if ($conn->query($alter_sql) === TRUE) {
        echo "Table users updated successfully (role column added).\n";
    } else {
        echo "Error updating table: " . $conn->error . "\n";
    }
} else {
    echo "Column 'role' already exists.\n";
}

// Check if admin exists
$admin_email = "admin@fundlink.com";
$check_admin = "SELECT id FROM users WHERE email = '$admin_email'";
$res = $conn->query($check_admin);

if ($res->num_rows == 0) {
    $password = password_hash("admin123", PASSWORD_DEFAULT);
    $insert_admin = "INSERT INTO users (username, email, password, full_name, balance, role) 
                     VALUES ('admin', '$admin_email', '$password', 'System Administrator', 0.00, 'admin')";
    
    if ($conn->query($insert_admin) === TRUE) {
        echo "Default admin account created (Email: admin@fundlink.com, Pass: admin123).\n";
    } else {
        echo "Error creating admin: " . $conn->error . "\n";
    }
} else {
    echo "Admin account already exists.\n";
}

$conn->close();
?>