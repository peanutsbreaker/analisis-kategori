<?php
session_start();
if (!isset($_SESSION['username'])) {
    exit("Unauthorized");
}

require 'db.php';

$username = $_SESSION['username'];

// Ambil user_id berdasarkan username
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user) {
    exit("User not found");
}

$user_id = $user['id'];

// Ambil gaji user dari tabel salaries
$stmt = $pdo->prepare("SELECT SUM(salary) AS total_salary FROM salaries WHERE user_id = ?");
$stmt->execute([$user_id]);
$result = $stmt->fetch();
$salary = $result ? $result['total_salary'] : 0;


header('Content-Type: application/json');
echo json_encode(['salary' => $salary]);
?>
