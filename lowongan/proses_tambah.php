<?php
session_start(); // [BARIS BARU] - Wajib untuk mengakses $_SESSION

// 1. Sertakan file koneksi
require '../koneksi.php';

// [OPSIONAL TAPI DIANJURKAN] - Tambahkan proteksi yang sama seperti di halaman form
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'perusahaan' || $_SESSION['user_status_verifikasi'] !== 'terverifikasi') {
    header("Location: ../auth/login.php");
    exit();
}

// 2. Periksa apakah data dikirim melalui metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 3. Ambil data dari form dan simpan ke variabel
    $judul = $_POST['judul'];
    $nama_perusahaan = $_POST['nama_perusahaan'];
    $lokasi = $_POST['lokasi'];
    $deskripsi = $_POST['deskripsi'];
    $id_perusahaan = $_SESSION['user_id']; // [BARIS BARU] - Ambil ID perusahaan dari session

    // 4. Siapkan query SQL untuk INSERT data (Gunakan Prepared Statements!)
    // [KUERI DIUBAH] - Menambahkan kolom `id_perusahaan` dan placeholder-nya
    $query = "INSERT INTO lowongan (id_perusahaan, judul, nama_perusahaan, lokasi, deskripsi) 
              VALUES (:id_perusahaan, :judul, :nama_perusahaan, :lokasi, :deskripsi)";
    
    $statement = $pdo->prepare($query);

    // 5. Ikat parameter dengan variabel
    $statement->bindParam(':id_perusahaan', $id_perusahaan); // [BARIS BARU] - Ikat parameter baru
    $statement->bindParam(':judul', $judul);
    $statement->bindParam(':nama_perusahaan', $nama_perusahaan);
    $statement->bindParam(':lokasi', $lokasi);
    $statement->bindParam(':deskripsi', $deskripsi);

    // 6. Eksekusi query untuk menyimpan data
    if ($statement->execute()) {
        // 7. Jika berhasil, alihkan (redirect) pengguna kembali ke halaman utama
        header("Location: ../index.php");
        exit(); // Penting untuk menghentikan eksekusi skrip setelah redirect
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Gagal menyimpan data.";
    }
} else {
    // Jika file diakses langsung tanpa melalui POST, alihkan ke halaman form
    header("Location: tambah.php");
    exit();
}
?>