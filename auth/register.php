<?php require '../templates/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                Registrasi Akun Baru
            </div>
            <div class="card-body">
                <form action="proses_register.php" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Alamat Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Saya mendaftar sebagai:</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="mahasiswa">Mahasiswa</option>
                            <option value="perusahaan">Perusahaan</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Daftar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require '../templates/footer.php'; ?>