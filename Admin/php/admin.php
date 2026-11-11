<?php
session_start();
require_once __DIR__ . '/../../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../Login_&_Register/index.php');
    exit();
}

$stmt = $conn->prepare('SELECT username, full_name, role FROM users WHERE id = ?');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();

if (!$admin || $admin['role'] !== 'admin') {
    header('Location: ../Dashboard/index.php');
    exit();
}

$user_count = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role = 'user'")->fetch_assoc()['total'];
$request_count = $conn->query("SELECT COUNT(*) AS total FROM loan_requests WHERE status = 'open'")->fetch_assoc()['total'];
$loan_count = $conn->query("SELECT COUNT(*) AS total FROM loans WHERE status = 'active'")->fetch_assoc()['total'];

$recent_users = [];
$result = $conn->query('SELECT full_name, username, email, role, created_at FROM users ORDER BY id DESC LIMIT 6');
while ($row = $result->fetch_assoc()) {
    $recent_users[] = $row;
}

$recent_requests = [];
$result = $conn->query("SELECT lr.title, lr.amount, lr.status, lr.created_at, u.full_name
                        FROM loan_requests lr
                        JOIN users u ON lr.user_id = u.id
                        ORDER BY lr.id DESC LIMIT 6");
while ($row = $result->fetch_assoc()) {
    $recent_requests[] = $row;
}

function admin_escape($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

require __DIR__ . '/../html/dashboard.php';