<?php
session_start();

// Menghapus semua sesi
session_unset();

// Menghancurkan sesi
session_destroy();

// Mengarahkan ke halaman login
header("Location: login.php");
exit();
?>
