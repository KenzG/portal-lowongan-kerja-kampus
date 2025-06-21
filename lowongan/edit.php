<?php
// Sertakan koneksi dan ambil data lowongan yang akan diedit
require '../koneksi.php';

// Cek apakah ada 'id' yang dikirim
if (isset($_GET['id'])) {
    $id_lowongan = $_GET['id'];
    
    $query = "SELECT * FROM lowongan WHERE id = :id";
    $statement = $pdo->prepare($query);
    $statement->bindParam(':id', $id_lowongan, PDO::PARAM_INT);
    $statement->execute();
    $lowongan = $statement->fetch();

    if (!$lowongan) {
        header("Location: ../index.php");
        exit();
    }
} else {
    header("Location: ../index.php");
    exit();
}

require '../templates/header.php';
?>

<div class="card">
    <div class="card-header bg-warning text-white">
        Form Edit Lowongan
    </div>
    <div class="card-body">
        <form action="proses_edit.php" method="POST">
            <input type="hidden" name="id" value="<?= $lowongan['id'] ?>">

            <div class="mb-3">
                <label for="judul" class="form-label">Judul Lowongan</label>
                <input type="text" class="form-control" id="judul" name="judul" value="<?= htmlspecialchars($lowongan['judul']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="nama_perusahaan" class="form-label">Nama Perusahaan</label>
                <input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan" value="<?= htmlspecialchars($lowongan['nama_perusahaan']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="lokasi" class="form-label">Lokasi</label>
                <input type="text" class="form-control" id="lokasi" name="lokasi" value="<?= htmlspecialchars($lowongan['lokasi']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi Pekerjaan</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5" required><?= htmlspecialchars($lowongan['deskripsi']) ?></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="detail.php?id=<?= $lowongan['id'] ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?php
require '../templates/footer.php';
?>