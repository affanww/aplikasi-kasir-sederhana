<?php 
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}
$halaman = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html> 
<html> 
<head> 
    <title>Aplikasi Kasir</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
rel="stylesheet"> 
</head> 
<body class="bg-light"> 
 
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4"> 
    <div class="container"> 
        <a class="navbar-brand fw-bold" href="index.php">KASIR APP</a> 
        <div class="navbar-nav me-auto"> 
            <a class="nav-link <?= ($halaman == 'barang') ? 'active fw-bold' : ''; ?>" 
href="index.php">1. Barang</a> 
            <a class="nav-link <?= ($halaman == 'transaksi') ? 'active fw-bold' : ''; ?>" 
href="transaksi.php">2. Transaksi</a> 
            <a class="nav-link <?= ($halaman == 'detail') ? 'active fw-bold' : ''; ?>" 
href="detail_transaksi.php">3. Detail Transaksi</a> 
        </div> 
        <span class="text-white me-3">Kasir: <?= $_SESSION['nama']; ?></span> 
        <a href="logout.php" class="btn btn-light btn-sm">Logout</a> 
    </div> 
</nav> 
 
<div class="container">