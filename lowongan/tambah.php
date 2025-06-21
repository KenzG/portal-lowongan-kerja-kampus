<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'perusahaan') {
    header("Location: ../auth/login.php");
    exit();
}
require '../templates/header.php';
?>

<div class="card">
    <div class="card-header bg-success text-white">
        Form Tambah Lowongan Baru
    </div>
    <div class="card-body">
        <form action="proses_tambah.php" method="POST">
            <div class="mb-3">
                <label for="judul" class="form-label">Judul Lowongan</label>
                <input type="text" class="form-control" id="judul" name="judul" required>
            </div>
            <div class="mb-3">
                <label for="nama_perusahaan" class="form-label">Nama Perusahaan</label>
                <input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan" required>
            </div>
            <div class="mb-3">
                <label for="lokasi" class="form-label">Lokasi</label>
                <input type="text" class="form-control" id="lokasi" name="lokasi" required>
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi Pekerjaan</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5" required></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Simpan Lowongan</button>
            <a href="../index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>


<?php
// Sertakan footer
require '../templates/footer.php';
?>