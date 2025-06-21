<?php
require '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil semua data dari form, termasuk ID yang tersembunyi
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $nama_perusahaan = $_POST['nama_perusahaan'];
    $lokasi = $_POST['lokasi'];
    $deskripsi = $_POST['deskripsi'];

    // Siapkan query SQL UPDATE
    $query = "UPDATE lowongan SET 
                judul = :judul, 
                nama_perusahaan = :nama_perusahaan, 
                lokasi = :lokasi, 
                deskripsi = :deskripsi 
              WHERE id = :id";
    
    $statement = $pdo->prepare($query);

    // Ikat semua parameter
    $statement->bindParam(':judul', $judul);
    $statement->bindParam(':nama_perusahaan', $nama_perusahaan);
    $statement->bindParam(':lokasi', $lokasi);
    $statement->bindParam(':deskripsi', $deskripsi);
    $statement->bindParam(':id', $id, PDO::PARAM_INT);

    // Eksekusi query
    if ($statement->execute()) {
        // Jika berhasil, redirect kembali ke halaman detail lowongan tersebut
        header("Location: detail.php?id=" . $id);
        exit();
    } else {
        echo "Gagal memperbarui data.";
    }
} else {
    header("Location: ../index.php");
    exit();
}
?>