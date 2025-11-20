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
    elseif ($jumlah > $data['tersedia']) $error = 'Jumlah melebihi stok tersedia.';
    else {
        // transaksi insert
        $stmt = $mysqli->prepare("INSERT INTO transaksi (barang_id, peminjam, jenis, jumlah, catatan) VALUES (?, ?, 'pinjam', ?, ?)");
        $stmt->bind_param('isds', $id, $peminjam, $jumlah, $catatan); // note: 'd' untuk int juga bisa, tapi pakai i,s,i,s lebih tepat
        // adjust: fix bind types properly:
        $stmt = $mysqli->prepare("INSERT INTO transaksi (barang_id, peminjam, jenis, jumlah, catatan) VALUES (?, ?, 'pinjam', ?, ?)");
        $stmt->bind_param('isis', $id, $peminjam, $jumlah, $catatan);

        if ($stmt->execute()) {
            // kurangi tersedia
            $stmt2 = $mysqli->prepare("UPDATE barang SET tersedia = tersedia - ? WHERE id = ?");
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
    <title>Pinjam Barang</title>
</head>

<body>
    <div class="container">
        <h2>Pinjam: <?= htmlspecialchars($data['nama']) ?></h2>
        <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <p>Stok tersedia: <?= $data['tersedia'] ?></p>
        <form method="post">
            <label>Nama Peminjam</label><br>
            <input type="text" name="peminjam" required><br>
            <label>Jumlah</label><br>
            <input type="number" name="jumlah" value="1" min="1" max="<?= $data['tersedia'] ?>" required><br>
            <label>Catatan</label><br>
            <textarea name="catatan"></textarea><br><br>
            <button type="submit">Pinjam</button>
        </form>
        <p><a href="index.php">Kembali</a></p>
    </div>
</body>

</html>