<?php
session_start();
if (!isset($_SESSION['username'])) exit("Unauthorized");

require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$expenseId = $data['id'] ?? null;

if (!$expenseId) exit("No ID provided");

$username = $_SESSION['username'];

// Ambil user_id berdasarkan username
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user) exit("User not found");

$user_id = $user['id'];

// Hapus pengeluaran berdasarkan id dan user_id
$stmt = $pdo->prepare("DELETE FROM expenses WHERE id = ? AND user_id = ?");
$stmt->execute([$expenseId, $user_id]);

echo "Deleted";
?>
