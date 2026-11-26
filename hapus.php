<?php
require 'koneksi.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM dokter WHERE id = ?");
    $stmt->execute([$id]);
}


header("Location: index.php?pesan=" . urlencode("Data dokter berhasil dihapus."));
exit;