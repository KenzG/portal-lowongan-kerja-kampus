<?php
require '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    // Siapkan query SQL DELETE
    $query = "DELETE FROM lowongan WHERE id = :id";
    $statement = $pdo->prepare($query);
    $statement->bindParam(':id', $id, PDO::PARAM_INT);

    // Eksekusi query
    if ($statement->execute()) {
        // Jika berhasil, redirect ke halaman utama karena halaman detail sudah tidak ada
        header("Location: ../index.php");
        exit();
    } else {
        echo "Gagal menghapus data.";
    }
} else {
    header("Location: ../index.php");
    exit();
}
?>