<?php
// Selalu mulai session
session_start();

// Hapus semua variabel session
session_unset();

// Hancurkan session
session_destroy();

// Redirect ke halaman login atau halaman utama
header("Location: login.php");
exit();
?>