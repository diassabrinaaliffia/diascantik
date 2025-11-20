<?php
require_once 'koneksi.php';
require_once 'helpers.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$stmt = $mysqli->prepare("SELECT * FROM barang WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
if (!$data = $res->fetch_assoc()) {
    die("Barang tidak ditemukan.");
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $jumlah = (int)$_POST['jumlah'];
    $lokasi = trim($_POST['lokasi']);
    $kode = trim($_POST['kode']);

    // adjust 'tersedia' bila total jumlah berubah
    $selisih = $jumlah - $data['jumlah'];
    $tersedia = $data['tersedia'] + $selisih;
    if ($tersedia < 0) $tersedia = 0;

    $stmt = $mysqli->prepare("UPDATE barang SET nama=?, deskripsi=?, jumlah=?, tersedia=?, lokasi=?, kode=? WHERE id=?");
    $stmt->bind_param('ssiissi', $nama, $deskripsi, $jumlah, $tersedia, $lokasi, $kode, $id);
    if ($stmt->execute()) {
        header('Location: index.php');
        exit;
    } else {
        $error = "Gagal update: " . $mysqli->error;
    }
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Edit Barang</title>
</head>

<body>
    <div class="container">
        <h2>Edit Barang</h2>
        <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <form method="post">
            <label>Nama</label><br>
            <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required><br>
            <label>Kode</label><br>
            <input type="text" name="kode" value="<?= htmlspecialchars($data['kode']) ?>"><br>
            <label>Deskripsi</label><br>
            <textarea name="deskripsi"><?= htmlspecialchars($data['deskripsi']) ?></textarea><br>
            <label>Jumlah (total)</label><br>
            <input type="number" name="jumlah" value="<?= $data['jumlah'] ?>" min="0" required><br>
            <label>Lokasi</label><br>
            <input type="text" name="lokasi" value="<?= htmlspecialchars($data['lokasi']) ?>"><br><br>
            <button type="submit">Update</button>
        </form>
        <p><a href="index.php">Kembali</a></p>
    </div>
</body>

</html>