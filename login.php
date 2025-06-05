<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek kredensial
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Username atau password salah. Silakan coba lagi.";
    }
} else {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Gagal</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .message-container {
            max-width: 500px;
            margin: 100px auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            padding: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .message-container::before {
            content: "";
            position: absolute;
            top: -50px;
            left: -50px;
            width: 100px;
            height: 100px;
            background: linear-gradient(45deg, #ff4757, #ffecec);
            border-radius: 50%;
            opacity: 0.1;
        }

        .error-icon {
            width: 80px;
            height: 80px;
            background: #ff4757;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .error-icon::after {
            content: "✕";
            color: white;
            font-size: 40px;
            font-weight: bold;
        }

        .message-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #ff4757;
        }

        .message-text {
            font-size: 16px;
            color: #7f8c8d;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .message-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-primary {
            background-color: #4a90e2;
            color: white;
        }

        .btn-primary:hover {
            background-color: #357ab8;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #ecf0f1;
            color: #2c3e50;
        }

        .btn-secondary:hover {
            background-color: #d5dbdb;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="message-container">
        <div class="error-icon"></div>
        <h1 class="message-title">Login Gagal</h1>
        <p class="message-text">
            <?= isset($error_message) ? $error_message : 'Username atau password salah.' ?><br>
            Pastikan Anda memasukkan data yang benar atau daftar terlebih dahulu jika belum memiliki akun.
        </p>
        <div class="message-actions">
            <a href="login.html" class="btn btn-primary">Coba Login Lagi</a>
            <a href="signup.html" class="btn btn-secondary">Daftar Akun</a>
        </div>
    </div>
</body>
</html>