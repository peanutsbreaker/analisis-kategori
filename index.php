<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <div class="container">
    <header>
      <h2>Halo, <?= htmlspecialchars($username) ?> | <a href="logout.php">Logout</a></h2>
    </header>

    <form id="salary-form">
      <input type="number" id="salary" placeholder="Masukkan Gaji Bulanan" required>
      <button type="submit">Simpan Gaji</button>
    </form>
    <h3>Riwayat Gaji</h3>
  <ul id="salary-history" class="salary-history"></ul>

  <h4>Saldo Saat Ini: 
  <span id="balance-display">Rp 0</span>
  <small>(Total Gaji - Total Pengeluaran)</small>
  </h4>
  
    <form id="expense-form">
      <input type="text" id="description" placeholder="Deskripsi" required>
      <input type="number" id="amount" placeholder="Jumlah (RP)" required>
      <select id="category" required>
        <option value="">Pilih Kategori</option>
        <option value="Makanan">Makanan</option>
        <option value="Transportasi">Transportasi</option>
        <option value="Hiburan">Hiburan</option>
        <option value="Pendidikan">Pendidikan</option>
        <option value="Lain-lain">Lain-lain</option>
      </select>
      <button type="submit">Tambahkan Pengeluaran</button>
    </form>

    <h3>Daftar Pengeluaran</h3>
    <table id="expense-table">
      <thead>
        <tr>
          <th>No</th>
          <th>Deskripsi</th>
          <th>Jumlah</th>
          <th>Kategori</th>
          <th>Tanggal</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>

    <h3>Analisis Kategori</h3>
    <canvas id="expense-chart" width="300" height="300"></canvas>
  </div>

  <script>
    const currentUser = '<?= htmlspecialchars($username) ?>';
  </script>
  <script src="script.js"></script>
</body>
</html>
