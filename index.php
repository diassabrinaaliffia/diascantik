<?php
require_once 'koneksi.php';
require_once 'helpers.php';
require_login();

// ambil list barang
$result = $mysqli->query("SELECT * FROM barang ORDER BY created_at DESC");
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Inventaris Barang</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="container">
        <h1>Inventaris Barang</h1>
        <p>Selamat datang, <?= htmlspecialchars($_SESSION['user_name']) ?> <br>

        </p>
        <p>
            <a href="barang_add.php">Tambah Barang</a> |
            <a href="transaksi.php">Lihat Transaksi</a> | <a href="logout.php">Logout</a>
        </p>

        <table border="1" cellpadding="6" cellspacing="0">
            <tr>

                <th>Kode</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Jumlah</th>
                <th>Tersedia</th>
                <th>Lokasi</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>

                    <td><?= htmlspecialchars($row['kode']) ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['deskripsi'])) ?></td>
                    <td><?= $row['jumlah'] ?></td>
                    <td><?= $row['tersedia'] ?></td>
                    <td><?= htmlspecialchars($row['lokasi']) ?></td>
                    <td>
                        <a href="barang_edit.php?id=<?= $row['id'] ?>">Edit</a> |
                        <a href="barang_delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus barang?')">Hapus</a> |
                        <?php if ($row['tersedia'] > 0): ?>
                            <a href="pinjam.php?id=<?= $row['id'] ?>">Pinjam</a>
                        <?php else: ?>
                            <span style="color:gray">Kosong</span>
                        <?php endif; ?>
                        <?php if ($row['jumlah'] > $row['tersedia']): ?>
                            | <a href="kembalikan.php?id=<?= $row['id'] ?>">Kembalikan</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>

</html>