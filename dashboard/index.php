<?php
session_start();
require '../koneksi.php';

// Proteksi Halaman: Hanya untuk perusahaan yang login dan terverifikasi
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'perusahaan' || $_SESSION['user_status_verifikasi'] !== 'terverifikasi') {
    header("Location: ../auth/login.php");
    exit();
}

$id_perusahaan = $_SESSION['user_id'];

// === Query 1: Mengambil daftar lowongan milik perusahaan ini ===
$stmt_lowongan = $pdo->prepare("SELECT * FROM lowongan WHERE id_perusahaan = :id_perusahaan ORDER BY tanggal_posting DESC");
$stmt_lowongan->bindParam(':id_perusahaan', $id_perusahaan);
$stmt_lowongan->execute();
$lowongan_saya = $stmt_lowongan->fetchAll();

// === Query 2: Mengambil daftar pelamar untuk lowongan perusahaan ini (menggunakan JOIN) ===
$stmt_pelamar = $pdo->prepare("
    SELECT 
        u.email AS email_pelamar, 
        l.judul AS judul_lowongan,
        la.tanggal_lamaran
    FROM lamaran AS la
    JOIN users AS u ON la.id_mahasiswa = u.id
    JOIN lowongan AS l ON la.id_lowongan = l.id
    WHERE l.id_perusahaan = :id_perusahaan
    ORDER BY la.tanggal_lamaran DESC
");
$stmt_pelamar->bindParam(':id_perusahaan', $id_perusahaan);
$stmt_pelamar->execute();
$daftar_pelamar = $stmt_pelamar->fetchAll();


require '../templates/header.php';
?>

<div class="container">
    <h1 class="mt-4">Dashboard Perusahaan</h1>
    <p>Selamat datang, <?= htmlspecialchars($_SESSION['user_email']) ?>.</p>

    <div class="card mb-4">
        <div class="card-header">Daftar Lowongan yang Anda Posting</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead><tr><th>Judul Lowongan</th><th>Tanggal Posting</th><th>Aksi</th></tr></thead>
                    <tbody>
                        <?php if (count($lowongan_saya) > 0): ?>
                            <?php foreach ($lowongan_saya as $lowongan): ?>
                                <tr>
                                    <td><?= htmlspecialchars($lowongan['judul']) ?></td>
                                    <td><?= date('d F Y', strtotime($lowongan['tanggal_posting'])) ?></td>
                                    <td><a href="../lowongan/detail.php?id=<?= $lowongan['id'] ?>" class="btn btn-info btn-sm">Lihat Detail</a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center">Anda belum mem-posting lowongan.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Daftar Pelamar Baru</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead><tr><th>Email Pelamar</th><th>Posisi yang Dilamar</th><th>Tanggal Melamar</th></tr></thead>
                    <tbody>
                        <?php if (count($daftar_pelamar) > 0): ?>
                            <?php foreach ($daftar_pelamar as $pelamar): ?>
                                <tr>
                                    <td><?= htmlspecialchars($pelamar['email_pelamar']) ?></td>
                                    <td><?= htmlspecialchars($pelamar['judul_lowongan']) ?></td>
                                    <td><?= date('d F Y', strtotime($pelamar['tanggal_lamaran'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center">Belum ada pelamar.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require '../templates/footer.php'; ?>