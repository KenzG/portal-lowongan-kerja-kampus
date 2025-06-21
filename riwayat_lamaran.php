<?php
session_start();
require 'koneksi.php';

// Proteksi Halaman: Hanya untuk mahasiswa yang login
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'mahasiswa') {
    header("Location: auth/login.php");
    exit();
}

$id_mahasiswa = $_SESSION['user_id'];

// Query untuk mengambil riwayat lamaran mahasiswa yang sedang login
// Kita JOIN tabel 'lamaran' dengan tabel 'lowongan'
$stmt = $pdo->prepare("
    SELECT 
        l.judul,
        l.nama_perusahaan,
        la.tanggal_lamaran
    FROM lamaran AS la
    JOIN lowongan AS l ON la.id_lowongan = l.id
    WHERE la.id_mahasiswa = :id_mahasiswa
    ORDER BY la.tanggal_lamaran DESC
");
$stmt->bindParam(':id_mahasiswa', $id_mahasiswa);
$stmt->execute();
$riwayat_lamaran = $stmt->fetchAll();

require 'templates/header.php';
?>

<div class="container">
    <h1 class="mt-4">Riwayat Lamaran Saya</h1>
    <p>Berikut adalah daftar pekerjaan yang telah Anda lamar.</p>

    <div class="card">
        <div class="card-header">
            Riwayat Lamaran
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Posisi Dilamar</th>
                            <th>Perusahaan</th>
                            <th>Tanggal Melamar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($riwayat_lamaran) > 0): ?>
                            <?php foreach ($riwayat_lamaran as $lamaran): ?>
                                <tr>
                                    <td><?= htmlspecialchars($lamaran['judul']) ?></td>
                                    <td><?= htmlspecialchars($lamaran['nama_perusahaan']) ?></td>
                                    <td><?= date('d F Y, H:i', strtotime($lamaran['tanggal_lamaran'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">Anda belum pernah melamar pekerjaan apapun.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require 'templates/footer.php'; ?>