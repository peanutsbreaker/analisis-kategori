<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$expenseId = $data['id'] ?? null;

if (!$expenseId) {
    http_response_code(400);
    echo json_encode(['error' => 'No ID provided']);
    exit;
}

$username = $_SESSION['username'];

try {
    // Ambil user_id berdasarkan username
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if (!$user) {
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
        exit;
    }

    $user_id = $user['id'];

    // Cek apakah expense exists dan milik user ini
    $stmt = $pdo->prepare("SELECT id FROM expenses WHERE id = ? AND user_id = ?");
    $stmt->execute([$expenseId, $user_id]);
    $expense = $stmt->fetch();

    if (!$expense) {
        http_response_code(404);
        echo json_encode(['error' => 'Expense not found or not owned by user']);
        exit;
    }

    // Hapus pengeluaran
    $stmt = $pdo->prepare("DELETE FROM expenses WHERE id = ? AND user_id = ?");
    $result = $stmt->execute([$expenseId, $user_id]);

    if ($result && $stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Expense deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete expense']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>