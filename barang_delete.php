<?php
require_once 'koneksi.php';
require_once 'helpers.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$stmt = $mysqli->prepare("DELETE FROM barang WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
header('Location: index.php');
exit;