<?php require '../templates/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                Login
            </div>
            <div class="card-body">
                <?php
                // Tampilkan pesan jika registrasi berhasil
                if (isset($_GET['status']) && $_GET['status'] == 'sukses_register') {
                    echo '<div class="alert alert-success">Registrasi berhasil! Silakan login.</div>';
                }
                // Tampilkan pesan jika login gagal
                if (isset($_GET['status']) && $_GET['status'] == 'gagal_login') {
                    echo '<div class="alert alert-danger">Email atau password salah.</div>';
                }
                ?>
                <form action="proses_login.php" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Alamat Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Login</button>
                </form>
            </div>
            <div class="card-footer text-center">
                Belum punya akun? <a href="register.php">Daftar di sini</a>
            </div>
        </div>
    </div>
</div>

<?php require '../templates/footer.php'; ?>