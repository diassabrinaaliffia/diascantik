<?php
require_once 'koneksi.php';
require_once 'helpers.php';
require_login();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $jumlah = (int)$_POST['jumlah'];
    $lokasi = trim($_POST['lokasi']);
    $kode = trim($_POST['kode']);

    if ($nama === '' || $jumlah < 0) $error = 'Nama dan jumlah harus diisi dengan benar.';
    else {
        $tersedia = $jumlah;
        $stmt = $mysqli->prepare("INSERT INTO barang (nama, deskripsi, jumlah, tersedia, lokasi, kode) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssiiss', $nama, $deskripsi, $jumlah, $tersedia, $lokasi, $kode);
        if ($stmt->execute()) {
            header('Location: index.php');
            exit;
        } else {
            $error = "Gagal menyimpan: " . $mysqli->error;
        }
    }
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Tambah Barang</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="container">
        <h2>Tambah Barang</h2>
        <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <form method="post">
            <label>Nama Barang</label><br>
            <input type="text" name="nama" required><br>
            <label>Kode (unik)</label><br>
            <input type="text" name="kode"><br>
            <label>Deskripsi</label><br>
            <textarea name="deskripsi"></textarea><br>
            <label>Jumlah</label><br>
            <input type="number" name="jumlah" value="1" min="0" required><br>
            <label>Lokasi</label><br>
            <input type="text" name="lokasi"><br><br>
            <button type="submit">Simpan</button>
            <button type="submit" onclick="window.location.href='index.php'">Kembali</button>
        </form>

    </div>
</body>

</html>