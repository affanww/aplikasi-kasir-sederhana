<?php 
session_start(); 
include 'connect.php'; 
 
if (!isset($_SESSION['id_user'])) { 
    header("Location: login.php"); 
    exit; 
} 
 
$halaman = 'detail'; 
include 'header.php'; 
?> 
 
<div class="card shadow-sm"> 
    <div class="card-header bg-dark text-white fw-bold">Riwayat & Detail Transaksi</div> 
    <div class="card-body"> 
        <table class="table table-bordered align-middle"> 
            <thead class="table-light"> 
                <tr> 
                    <th>Kode Struk</th> 
                    <th>Tanggal</th> 
                    <th>Pelanggan</th> 
                    <th>Kasir</th> 
                    <th>Barang yang Dibeli</th> 
                    <th>Total Bayar</th> 
                </tr> 
            </thead> 
            <tbody> 
                <?php 
                // Query 1: Menggabungkan tabel transaksi dan users 
                $query_trx = mysqli_query($koneksi, "SELECT transaksi.*, users.nama  
                                                     FROM transaksi  
                                                     JOIN users ON transaksi.id_user = 
users.id_user  
                                                     ORDER BY id_transaksi DESC"); 
 
                while ($trx = mysqli_fetch_assoc($query_trx)) { 
                    $id_trx = $trx['id_transaksi']; 
                ?> 
                <tr> 
                    <td class="fw-bold text-primary"><?= $trx['kode_struk']; ?></td> 
                    <td><?= $trx['tanggal_transaksi']; ?></td> 
                    <td><?= $trx['nama_pelanggan']; ?></td> 
                    <td><?= $trx['nama']; ?></td> 
                    <td> 
                        <ul class="mb-0"> 
                        <?php 
                        // Query 2: Menggabungkan detail_transaksi dan barang berdasarkan id_transaksi 
                        $query_detail = mysqli_query($koneksi, "SELECT detail_transaksi.*, barang.nama_barang  
                                                                FROM detail_transaksi  
                                                                JOIN barang ON detail_transaksi.id_barang = barang.id_barang  
                                                                WHERE id_transaksi='$id_trx'"); 
                        while ($dt = mysqli_fetch_assoc($query_detail)) { 
                            echo "<li>$dt[nama_barang] ($dt[jumlah] x Rp " . 
number_format($dt['harga_satuan']) . ") = Rp " . number_format($dt['subtotal']) . "</li>"; 
                        } 
                        ?> 
                        </ul> 
                    </td> 
                    <td class="fw-bold text-success">Rp <?= number_format($trx['total_bayar']); 
?></td> 
                </tr> 
                <?php } ?> 
            </tbody> 
        </table> 

    </div> 
</div> 
</div> 
</body> 
</html>