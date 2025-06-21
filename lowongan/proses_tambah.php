<?php
// 1. Sertakan file koneksi
require '../koneksi.php';

// 2. Periksa apakah data dikirim melalui metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 3. Ambil data dari form dan simpan ke variabel
    $judul = $_POST['judul'];
    $nama_perusahaan = $_POST['nama_perusahaan'];
    $lokasi = $_POST['lokasi'];
    $deskripsi = $_POST['deskripsi'];

    // 4. Siapkan query SQL untuk INSERT data (Gunakan Prepared Statements!)
    $query = "INSERT INTO lowongan (judul, nama_perusahaan, lokasi, deskripsi) 
              VALUES (:judul, :nama_perusahaan, :lokasi, :deskripsi)";
    
    $statement = $pdo->prepare($query);

    // 5. Ikat parameter dengan variabel
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