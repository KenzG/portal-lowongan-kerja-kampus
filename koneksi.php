<?php
// Informasi untuk koneksi database
$host = 'localhost';
$dbname = 'db_portal_kampus'; // Nama database Anda
$user = 'root'; // Username default XAMPP
$pass = ''; // Password default XAMPP kosong

try {
    // Buat objek PDO untuk koneksi
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // Set mode error PDO ke exception agar error terlihat jelas
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set mode fetch default agar hasil query berupa array asosiatif
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Jika koneksi gagal, hentikan skrip dan tampilkan pesan error
    die("Koneksi ke database gagal: " . $e->getMessage());
}
?>