<?php
// --- [BLOK LOGIKA UTAMA LENGKAP] ---

// 1. Memulai semua service yang dibutuhkan: koneksi dan session
require 'koneksi.php';
// 'header.php' akan memulai session, jadi kita panggil di sini
require 'templates/header.php'; 

// 2. Pengaturan untuk Pagination
$halaman_sekarang = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
// Pastikan halaman tidak kurang dari 1
if ($halaman_sekarang < 1) {
    $halaman_sekarang = 1;
}
$item_per_halaman = 9; // Tampilkan 9 lowongan per halaman

// 3. Logika untuk Pencarian
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$base_query = "FROM lowongan";
$kondisi_where = "";
$params = []; // Siapkan array untuk menampung parameter query

// Jika ada keyword pencarian, bangun kondisi WHERE
if (!empty(trim($keyword))) {
    $kondisi_where = " WHERE judul LIKE :keyword OR nama_perusahaan LIKE :keyword";
    $params[':keyword'] = '%' . $keyword . '%';
}

// 4. Hitung Total Item untuk Pagination
// Query ini menghitung jumlah total lowongan (baik dengan atau tanpa filter pencarian)
$query_total = "SELECT COUNT(*) " . $base_query . $kondisi_where;
$stmt_total = $pdo->prepare($query_total);
$stmt_total->execute($params);
$total_item = $stmt_total->fetchColumn();
$total_halaman = ceil($total_item / $item_per_halaman);

// Pastikan halaman sekarang tidak melebihi total halaman yang ada
if ($halaman_sekarang > $total_halaman && $total_halaman > 0) {
    $halaman_sekarang = $total_halaman;
}

// 5. Hitung OFFSET (mulai dari data ke berapa) untuk query utama
$offset = ($halaman_sekarang - 1) * $item_per_halaman;

// 6. Query utama untuk mengambil data lowongan dengan LIMIT dan OFFSET
$query_utama = "SELECT id, judul, nama_perusahaan, lokasi " . $base_query . $kondisi_where . " ORDER BY tanggal_posting DESC LIMIT :limit OFFSET :offset";
$statement = $pdo->prepare($query_utama);

// Ikat parameter dari pencarian (jika ada)
if (!empty($params)) {
    foreach ($params as $key => &$val) {
        $statement->bindParam($key, $val);
    }
}
// Ikat parameter untuk LIMIT dan OFFSET
$statement->bindParam(':limit', $item_per_halaman, PDO::PARAM_INT);
$statement->bindParam(':offset', $offset, PDO::PARAM_INT);
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

<h2 class="mb-4">
    <?php 
    // Ganti judul berdasarkan apakah ada pencarian atau tidak
    if (!empty(trim($keyword))) {
        echo 'Hasil Pencarian untuk: "' . htmlspecialchars($keyword) . '"';
    } else {
        echo 'Lowongan Terbaru';
    }
    ?>
</h2>

<div class="row">
    <?php if ($statement->rowCount() > 0): ?>
        <?php while ($lowongan = $statement->fetch()): ?>
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
        <?php endwhile; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-warning text-center">
                Tidak ada lowongan yang ditemukan.
            </div>
        </div>
    <?php endif; ?>
</div> <nav aria-label="Page navigation">
    <ul class="pagination justify-content-center mt-4">
        <?php if ($total_halaman > 1): ?>
            <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
                <li class="page-item <?= ($i == $halaman_sekarang) ? 'active' : '' ?>">
                    <a class="page-link" href="?halaman=<?= $i ?>&keyword=<?= htmlspecialchars($keyword) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        <?php endif; ?>
    </ul>
</nav>

<?php
// Memanggil file footer.php untuk menampilkan bagian bawah halaman
require 'templates/footer.php';