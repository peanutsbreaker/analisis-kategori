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
        $error_message = "Username sudah dipakai. Silakan pilih username lain.";
    } else {
        // Simpan pengguna
        $stmt = $pdo->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$email, $username, $password])) {
            $success = true;
            $success_message = "Registrasi berhasil! Selamat datang, " . htmlspecialchars($username) . "!";
        } else {
            $error_message = "Terjadi kesalahan saat mendaftarkan akun. Silakan coba lagi.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($success) ? 'Registrasi Berhasil' : 'Error Registrasi' ?></title>
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
            background: linear-gradient(45deg, #4a90e2, #e0ecff);
            border-radius: 50%;
            opacity: 0.1;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: #4CAF50;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .success-icon::after {
            content: "✓";
            color: white;
            font-size: 40px;
            font-weight: bold;
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
            color: #2c3e50;
        }

        .success-title {
            color: #4CAF50;
        }

        .error-title {
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

        .countdown {
            font-size: 14px;
            color: #95a5a6;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="message-container">
        <?php if (isset($success) && $success): ?>
            <!-- SUCCESS MESSAGE -->
            <div class="success-icon"></div>
            <h1 class="message-title success-title">Registrasi Berhasil!</h1>
            <p class="message-text">
                Selamat datang, <strong><?= htmlspecialchars($username) ?></strong>!<br>
                Akun Anda telah berhasil dibuat. Silakan login untuk mulai mengelola keuangan Anda.
            </p>
            <div class="message-actions">
                <a href="login.html" class="btn btn-primary">Login Sekarang</a>
                <a href="signup.html" class="btn btn-secondary">Daftar Lagi</a>
            </div>
            <div class="countdown">
                <small>Otomatis diarahkan ke halaman login dalam <span id="countdown">5</span> detik</small>
            </div>
            
            <script>
                // Auto redirect ke login setelah 5 detik
                let timeLeft = 5;
                const countdownEl = document.getElementById('countdown');
                
                const timer = setInterval(() => {
                    timeLeft--;
                    countdownEl.textContent = timeLeft;
                    
                    if (timeLeft <= 0) {
                        clearInterval(timer);
                        window.location.href = 'login.html';
                    }
                }, 1000);
            </script>
            
        <?php else: ?>
            <!-- ERROR MESSAGE -->
            <div class="error-icon"></div>
            <h1 class="message-title error-title">Registrasi Gagal</h1>
            <p class="message-text">
                <?= isset($error_message) ? $error_message : 'Terjadi kesalahan yang tidak diketahui.' ?>
            </p>
            <div class="message-actions">
                <a href="signup.html" class="btn btn-primary">Coba Lagi</a>
                <a href="login.html" class="btn btn-secondary">Login</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>