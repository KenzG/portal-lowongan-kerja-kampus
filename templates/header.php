<?php
// Selalu mulai session di baris paling atas
session_start();
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal Lowongan Kerja Kampus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
      <div class="container">
        <a class="navbar-brand" href="/portal-lowongan-kerja-kampus/">Portal Lowongan Kampus</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <span class="nav-link">Selamat datang, <?= htmlspecialchars($_SESSION['user_email']) ?></span>
                    </li>
                    
                    <?php 
                    // Mulai satu rangkaian pengecekan role
                    if ($_SESSION['user_role'] == 'perusahaan'): ?>
                        <?php if ($_SESSION['user_status_verifikasi'] == 'terverifikasi'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/portal-lowongan-kerja-kampus/dashboard/">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/portal-lowongan-kerja-kampus/lowongan/tambah.php">Post Lowongan</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <span class="nav-link disabled text-warning">Akun Menunggu Verifikasi</span>
                            </li>
                        <?php endif; ?>
                    
                    <?php elseif ($_SESSION['user_role'] == 'mahasiswa'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/portal-lowongan-kerja-kampus/riwayat_lamaran.php">Riwayat Lamaran</a>
                        </li>

                    <?php elseif ($_SESSION['user_role'] == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link fw-bold text-warning" href="/portal-lowongan-kerja-kampus/admin/">Admin Dashboard</a>
                        </li>
                    <?php endif; // Penutup untuk rangkaian pengecekan role ?>

                    <li class="nav-item">
                        <a class="nav-link" href="/portal-lowongan-kerja-kampus/auth/logout.php">Logout</a>
                    </li>

                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/portal-lowongan-kerja-kampus/auth/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/portal-lowongan-kerja-kampus/auth/register.php">Register</a>
                    </li>
                <?php endif; // Penutup untuk pengecekan isset($_SESSION['user_id']) ?>

            </ul>
        </div>
      </div>
    </nav>
    
    <main class="container mt-4">