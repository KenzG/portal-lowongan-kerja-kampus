<?php
// Selalu mulai session di baris paling atas untuk mengakses variabel $_SESSION
session_start();

// 1. Sertakan file koneksi.php
// Gunakan '../' karena file ini berada di dalam subfolder 'lowongan'
require '../koneksi.php';

// 2. Cek apakah ada 'id' yang dikirim melalui URL
if (isset($_GET['id'])) {
    $id_lowongan = $_GET['id'];

    // 3. Gunakan Prepared Statements untuk keamanan dari SQL Injection
    $query = "SELECT * FROM lowongan WHERE id = :id";
    $statement = $pdo->prepare($query);
    
    // 4. "Ikat" parameter :id dengan variabel $id_lowongan
    $statement->bindParam(':id', $id_lowongan, PDO::PARAM_INT);
    
    // 5. Eksekusi query
    $statement->execute();
    
    // 6. Ambil datanya
    $lowongan = $statement->fetch();

    // Jika lowongan dengan ID tersebut tidak ditemukan, alihkan pengguna ke halaman utama
    if (!$lowongan) {
        header("Location: ../index.php");
        exit();
    }
} else {
    // Jika tidak ada ID di URL, alihkan juga ke halaman utama
    header("Location: ../index.php");
    exit();
}

// 7. Sertakan file header.php (yang juga sudah memanggil session_start)
require '../templates/header.php'; 
?>

<div class="card">
    <div class="card-header bg-primary text-white">
        Detail Lowongan
    </div>
    <div class="card-body">
        <h2 class="card-title"><?= htmlspecialchars($lowongan['judul']) ?></h2>
        <h4 class="card-subtitle mb-3 text-muted"><?= htmlspecialchars($lowongan['nama_perusahaan']) ?></h4>
        
        <p><strong>Lokasi:</strong> <?= htmlspecialchars($lowongan['lokasi']) ?></p>
        
        <hr>
        
        <h5>Deskripsi Pekerjaan:</h5>
        <p><?= nl2br(htmlspecialchars($lowongan['deskripsi'])) ?></p> 
        
        <hr>
        
        <p class="text-muted"><small>Diposting pada: <?= date('d F Y', strtotime($lowongan['tanggal_posting'])) ?></small></p>
        
        <a href="../index.php" class="btn btn-secondary">Kembali ke Daftar Lowongan</a>
        
        <?php
        // 8. Tampilkan tombol Edit dan Hapus HANYA JIKA user sudah login DAN role-nya adalah 'perusahaan'
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'perusahaan'):
        ?>
            <a href="edit.php?id=<?= $lowongan['id'] ?>" class="btn btn-warning">Edit</a>
            
            <form action="hapus.php" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?');">
                <input type="hidden" name="id" value="<?= $lowongan['id'] ?>">
                <button type="submit" class="btn btn-danger">Hapus</button>
            </form>
        <?php endif; ?>

    </div>
</div>

<?php
// 9. Sertakan footer
require '../templates/footer.php';
?>