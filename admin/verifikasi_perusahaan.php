<?php
session_start();
require '../koneksi.php';

// Proteksi Halaman
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_perusahaan'])) {
    $id_perusahaan = $_POST['id_perusahaan'];

    $stmt = $pdo->prepare("UPDATE users SET status_verifikasi = 'terverifikasi' WHERE id = :id");
    $stmt->bindParam(':id', $id_perusahaan, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        die("Gagal melakukan verifikasi.");
    }
} else {
    header("Location: index.php");
    exit();
}
?>