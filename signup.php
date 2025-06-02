<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Cek username
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        echo "Username sudah dipakai. <a href='signup.html'>Coba lagi</a>";
        exit();
    }

    // Simpan pengguna
    $stmt = $pdo->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
    if ($stmt->execute([$email, $username, $password])) {
        echo "Registrasi berhasil. <a href='login.html'>Login</a>";
    } else {
        echo "Terjadi kesalahan. Silakan coba lagi.";
    }
}
?>