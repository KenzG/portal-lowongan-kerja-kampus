<?php
// [BAGIAN YANG DIUBAH] - Logika baru yang lebih lengkap
// 1. Sertakan koneksi dan header
require 'koneksi.php';
require 'templates/header.php'; // Pindahkan header ke sini agar session sudah aktif

// 2. Logika untuk Pencarian
$keyword = '';
if (isset($_GET['keyword']) && !empty(trim($_GET['keyword']))) {
    // JIKA ADA PENCARIAN
    $keyword = $_GET['keyword'];
    $query = "SELECT id, judul, nama_perusahaan, lokasi FROM lowongan 
              WHERE judul LIKE :keyword OR nama_perusahaan LIKE :keyword 
              ORDER BY tanggal_posting DESC";
    
    $statement = $pdo->prepare($query);
    // Tambahkan wildcard '%' agar pencarian bisa menemukan kata di tengah kalimat
    $search_keyword = '%' . $keyword . '%';
    $statement->bindParam(':keyword', $search_keyword);

} else {
    // JIKA TIDAK ADA PENCARIAN (tampilan default)
    $query = "SELECT id, judul, nama_perusahaan, lokasi FROM lowongan 
              ORDER BY tanggal_posting DESC";
    $statement = $pdo->prepare($query);
}

// 3. Eksekusi query yang sudah disiapkan
$statement->execute();
?>

<div class="p-5 mb-4 bg-light rounded-3">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold">Temukan Peluang Karirmu</h1>
        <p class="col-md-8 fs-4">Jelajahi berbagai lowongan magang dan pekerjaan yang tersedia khusus untuk mahasiswa.</p>
    </div>
</div>

<?php
// Tampilkan form pencarian hanya jika pengguna BUKAN perusahaan
// Ini berarti form akan tampil untuk pengunjung (guest) dan mahasiswa
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'perusahaan'):
?>

    <div class="row mb-4">
        <div class="col-md-8 offset-md-2">
            <form action="index.php" method="GET" class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Cari lowongan (judul/perusahaan)..." name="keyword" value="<?= htmlspecialchars($keyword) ?>">
                <button class="btn btn-outline-success" type="submit">Cari</button>
            </form>
        </div>
    </div>

<?php endif; ?>

<h2 class="mb-4">Lowongan Terbaru</h2>

<div class="row">
    <?php
    // 4. Looping (perulangan) untuk menampilkan setiap baris data dari hasil query
    while ($lowongan = $statement->fetch()) {
    ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= htmlspecialchars($lowongan['judul']) ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($lowongan['nama_perusahaan']) ?></h6>
                    <p class="card-text">
                        <small>Lokasi: <?= htmlspecialchars($lowongan['lokasi']) ?></small>
                    </p>
                    <a href="lowongan/detail.php?id=<?= $lowongan['id'] ?>" class="btn btn-primary mt-auto">Lihat Detail</a>
                </div>
            </div>
        </div>
    <?php
    } // 5. Akhir dari perulangan
    ?>
</div>

<?php
// 6. Memanggil file footer.php untuk menampilkan bagian bawah halaman
require 'templates/footer.php';
?>