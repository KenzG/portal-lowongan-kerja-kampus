<?php
session_start(); // Wajib ada untuk memulai session
require '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Cari user berdasarkan email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch();

    // Jika user ditemukan DAN password cocok
    if ($user && password_verify($password, $user['password'])) {
        // Login berhasil, simpan informasi user ke dalam session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];

        // Arahkan ke halaman utama
        header("Location: ../index.php");
        exit();
    } else {
        // Login gagal
        header("Location: login.php?status=gagal_login");
        exit();
    }
}
?>