<?php 
session_start();
if (!isset($_SESSION['username'])) exit("Unauthorized");
require 'db.php';

$username = $_SESSION['username'];

// Ambil user_id
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();
if (!$user) exit("User not found");
$user_id = $user['id'];

// Ambil data
$data = json_decode(file_get_contents("php://input"), true);
$salary = isset($data['salary']) ? floatval($data['salary']) : 0;

// Simpan gaji
$stmt = $pdo->prepare("INSERT INTO salaries (user_id, salary, date) VALUES (?, ?, NOW())");
$stmt->execute([$user_id, $salary]);
?>