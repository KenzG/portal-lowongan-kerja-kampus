<?php
require '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validasi sederhana (bisa dikembangkan lebih lanjut)
    if (empty($email) || empty($password) || empty($role)) {
        die("Email, password, dan role harus diisi.");
    }

    // Cek apakah email sudah terdaftar
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    if ($stmt->fetch()) {
        die("Email sudah terdaftar. Silakan gunakan email lain.");
    }

    // Hash password untuk keamanan
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Simpan user baru ke database
    $query = "INSERT INTO users (email, password, role) VALUES (:email, :password, :role)";
    $statement = $pdo->prepare($query);
    $statement->bindParam(':email', $email);
    $statement->bindParam(':password', $hashed_password);
    $statement->bindParam(':role', $role);

    if ($statement->execute()) {
        // Jika berhasil, arahkan ke halaman login
        header("Location: login.php?status=sukses_register");
        exit();
    } else {
        die("Registrasi gagal. Silakan coba lagi.");
    }
}
?>