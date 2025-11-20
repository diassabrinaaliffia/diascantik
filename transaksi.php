<?php
require_once 'koneksi.php';
require_once 'helpers.php';
require_login();

$sql = "SELECT t.*, b.nama AS nama_barang FROM transaksi t JOIN barang b ON t.barang_id = b.id ORDER BY t.tanggal DESC";
$res = $mysqli->query($sql);
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Transaksi</title>
</head>

<body>
    <div class="container">
        <h2>Daftar Transaksi</h2>
        <p><a href="index.php">Kembali</a></p>
        <table border="1" cellpadding="6" cellspacing="0">
            <tr>

                <th>Tanggal</th>
                <th>Barang</th>
                <th>Peminjam</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Catatan</th>
            </tr>
            <?php while ($row = $res->fetch_assoc()): ?>
                <tr>

                    <td><?= $row['tanggal'] ?></td>
                    <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                    <td><?= htmlspecialchars($row['peminjam']) ?></td>
                    <td><?= $row['jenis'] ?></td>
                    <td><?= $row['jumlah'] ?></td>
                    <td><?= htmlspecialchars($row['catatan']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>

</html>