<?php
session_start();
if (!isset($_SESSION['username'])) {
    http_response_code(403);
    exit("Unauthorized");
}
require 'db.php';

$username = $_SESSION['username'];

// Ambil user_id dari users
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();
if (!$user) exit("User not found");
$user_id = $user['id'];

// Ambil pengeluaran
$stmt = $pdo->prepare("SELECT id, description, amount, category, date FROM expenses WHERE user_id = ?");
$stmt->execute([$user_id]);
$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil gaji terakhir
$stmt = $pdo->prepare("SELECT SUM(salary) AS total_salary FROM salaries WHERE user_id = ?");
$stmt->execute([$user_id]);
$salaryRow = $stmt->fetch();
$salary = $salaryRow ? $salaryRow['total_salary'] : 0;

echo json_encode(["expenses" => $expenses, "salary" => $salary]);
?>