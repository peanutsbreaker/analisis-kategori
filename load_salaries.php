<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    exit(json_encode(["error" => "Anda belum login!"]));
}

require 'db.php';

try {
    // Ambil user_id
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$_SESSION['username']]);
    $user = $stmt->fetch();

    if (!$user) {
        http_response_code(404);
        exit(json_encode(["error" => "User tidak ditemukan"]));
    }

    // Ambil riwayat gaji
    $stmt = $pdo->prepare("
        SELECT 
            salary, 
            DATE_FORMAT(date, '%d %b %Y %H:%i') as date 
        FROM salaries 
        WHERE user_id = ? 
        ORDER BY date DESC
    ");
    $stmt->execute([$user['id']]);
    $salaries = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "data" => $salaries
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Database error: " . $e->getMessage()
    ]);
}
?>