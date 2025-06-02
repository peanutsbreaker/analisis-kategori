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

// Ambil data dari request
$data = json_decode(file_get_contents("php://input"), true);
$description = $data['description'] ?? '';
$amount = floatval($data['amount'] ?? 0);
$category = $data['category'] ?? 'Lainnya';

// Simpan ke tabel expenses
$stmt = $pdo->prepare("INSERT INTO expenses (user_id, description, amount, category, date) VALUES (?, ?, ?, ?, NOW())");
$stmt->execute([$user_id, $description, $amount, $category]);
?>
