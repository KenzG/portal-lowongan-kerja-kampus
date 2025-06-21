<?php
session_start();
require '../koneksi.php';

// Proteksi Halaman Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Ambil semua perusahaan yang statusnya 'menunggu'
$stmt = $pdo->prepare("SELECT id, email FROM users WHERE role = 'perusahaan' AND status_verifikasi = 'menunggu'");
$stmt->execute();
$perusahaan_menunggu = $stmt->fetchAll();

require '../templates/header.php';
?>

<div class="container">
    <h1 class="mt-4">Dashboard Admin</h1>
    <p>Selamat datang, <?= htmlspecialchars($_SESSION['user_email']) ?>.</p>

    <div class="card">
        <div class="card-header">
            Perusahaan Menunggu Verifikasi
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Email Perusahaan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($perusahaan_menunggu) > 0): ?>
                        <?php foreach ($perusahaan_menunggu as $perusahaan): ?>
                            <tr>
                                <td><?= htmlspecialchars($perusahaan['email']) ?></td>
                                <td>
                                    <form action="verifikasi_perusahaan.php" method="POST" class="d-inline">
                                        <input type="hidden" name="id_perusahaan" value="<?= $perusahaan['id'] ?>">
                                        <button type="submit" class="btn btn-success btn-sm">Verifikasi</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" class="text-center">Tidak ada perusahaan yang menunggu verifikasi.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require '../templates/footer.php'; ?>