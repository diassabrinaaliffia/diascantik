<?php
require_once 'koneksi.php';
require_once 'helpers.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$stmt = $mysqli->prepare("SELECT * FROM barang WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
if (!$data) die('Barang tidak ditemukan.');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $peminjam = trim($_POST['peminjam']);
    $jumlah = (int)$_POST['jumlah'];
    $catatan = trim($_POST['catatan']);

    if ($peminjam === '' || $jumlah <= 0) $error = 'Isi peminjam dan jumlah dengan benar.';
    else {
        // insert transaksi kembali
        $stmt = $mysqli->prepare("INSERT INTO transaksi (barang_id, peminjam, jenis, jumlah, catatan) VALUES (?, ?, 'kembali', ?, ?)");
        $stmt->bind_param('isis', $id, $peminjam, $jumlah, $catatan);
        if ($stmt->execute()) {
            // tambahkan tersedia (tidak boleh melebihi jumlah total)
            $stmt2 = $mysqli->prepare("UPDATE barang SET tersedia = LEAST(jumlah, tersedia + ?) WHERE id = ?");
            $stmt2->bind_param('ii', $jumlah, $id);
            $stmt2->execute();
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
    <title>Kembalikan Barang</title>
</head>

<body>
    <div class="container">
        <h2>Kembalikan: <?= htmlspecialchars($data['nama']) ?></h2>
        <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <form method="post">
            <label>Nama Pengembali (atau nama peminjam)</label><br>
            <input type="text" name="peminjam" required><br>
            <label>Jumlah yang Dikembalikan</label><br>
            <input type="number" name="jumlah" value="1" min="1" required><br>
            <label>Catatan</label><br>
            <textarea name="catatan"></textarea><br><br>
            <button type="submit">Kembalikan</button>
        </form>
        <p><a href="index.php">Kembali</a></p>
    </div>
</body>

</html>