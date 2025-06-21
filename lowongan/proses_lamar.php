<?php
session_start();
require '../koneksi.php';

// Proteksi 1: Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: /portal-lowongan-kerja-kampus/auth/login.php");
    exit();
}

// Proteksi 2: Pastikan yang melamar adalah mahasiswa
if ($_SESSION['user_role'] !== 'mahasiswa') {
    // Jika bukan mahasiswa, bisa arahkan ke halaman utama atau tampilkan pesan
    header("Location: /portal-lowongan-kerja-kampus/index.php");
    exit();
}

// Proteksi 3: Pastikan data dikirim via POST dan id_lowongan ada
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_lowongan'])) {
    
    $id_lowongan = $_POST['id_lowongan'];
    $id_mahasiswa = $_SESSION['user_id'];

    // Proteksi 4: Cek lagi di server apakah sudah pernah melamar (untuk keamanan)
    $stmt_cek = $pdo->prepare("SELECT id FROM lamaran WHERE id_lowongan = :id_lowongan AND id_mahasiswa = :id_mahasiswa");
    $stmt_cek->bindParam(':id_lowongan', $id_lowongan);
    $stmt_cek->bindParam(':id_mahasiswa', $id_mahasiswa);
    $stmt_cek->execute();
    
    if ($stmt_cek->fetch()) {
        // Jika sudah ada, jangan proses, kembalikan saja
        header("Location: detail.php?id=" . $id_lowongan . "&status=sudah_melamar");
        exit();
    }

    // Jika semua proteksi lolos, masukkan data lamaran baru
    $stmt_insert = $pdo->prepare("INSERT INTO lamaran (id_lowongan, id_mahasiswa) VALUES (:id_lowongan, :id_mahasiswa)");
    $stmt_insert->bindParam(':id_lowongan', $id_lowongan);
    $stmt_insert->bindParam(':id_mahasiswa', $id_mahasiswa);
    
    if ($stmt_insert->execute()) {
        // Jika berhasil, redirect kembali ke halaman detail dengan status sukses
        header("Location: detail.php?id=" . $id_lowongan . "&status=lamaran_sukses");
        exit();
    } else {
        // Jika gagal
        header("Location: detail.php?id=" . $id_lowongan . "&status=lamaran_gagal");
        exit();
    }
} else {
    // Jika akses tidak valid, tendang ke halaman utama
    header("Location: /portal-lowongan-kerja-kampus/index.php");
    exit();
}
?>