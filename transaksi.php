<?php 
session_start(); 
include 'connect.php'; 
$halaman = 'detail'; 
include 'header.php'; 
 
if (!isset($_SESSION['id_user'])) { 
    header("Location: login.php"); 
    exit; 
} 
 
// 1. MEMBUAT KODE STRUK OTOMATIS (TR0001, TR0002, dst) 
$query_kode = mysqli_query($koneksi, "SELECT MAX(kode_struk) AS kode_terakhir FROM transaksi"); 
$data_kode  = mysqli_fetch_assoc($query_kode); 
$kode_lama  = $data_kode['kode_terakhir']; 
 
$urutan     = (int) substr($kode_lama, 2, 4); 
$urutan++; 
$kode_struk = "TR" . str_pad($urutan, 4, "0", STR_PAD_LEFT); 
 
// 2. PROSES SIMPAN TRANSAKSI 
if (isset($_POST['simpan_transaksi'])) { 
    $kode_struk     = $_POST['kode_struk']; 
    $id_user        = $_SESSION['id_user']; 
    $nama_pelanggan = $_POST['nama_pelanggan']; 
 
    // Menangkap input array (banyak barang) 
    $id_barang_arr  = $_POST['id_barang']; 
    $jumlah_arr     = $_POST['jumlah']; 
 
    // Hitung total bayar keseluruhan 
    $total_bayar = 0; 
    for ($i = 0; $i < count($id_barang_arr); $i++) { 
        $id_b = $id_barang_arr[$i]; 
        $qty  = $jumlah_arr[$i]; 
 
        $q_harga  = mysqli_query($koneksi, "SELECT harga FROM barang WHERE id_barang='$id_b'"); 
        $d_harga  = mysqli_fetch_assoc($q_harga); 
        $subtotal = $d_harga['harga'] * $qty; 
 
        $total_bayar = $total_bayar + $subtotal; 
    } 
 
    // Simpan ke tabel induk: TRANSAKSI 
    mysqli_query($koneksi, "INSERT INTO transaksi (kode_struk, id_user, nama_pelanggan, 
total_bayar)  
                            VALUES ('$kode_struk', '$id_user', '$nama_pelanggan', 
'$total_bayar')"); 
 
    // Ambil id_transaksi yang baru saja tersimpan 
    $id_transaksi_baru = mysqli_insert_id($koneksi); 
 
    // Simpan rincian barang satu per satu ke tabel anak: DETAIL_TRANSAKSI 
    for ($i = 0; $i < count($id_barang_arr); $i++) { 
        $id_b = $id_barang_arr[$i]; 
        $qty  = $jumlah_arr[$i]; 
 
        $q_harga      = mysqli_query($koneksi, "SELECT harga FROM barang WHERE 
id_barang='$id_b'"); 
        $d_harga      = mysqli_fetch_assoc($q_harga); 
        $harga_satuan = $d_harga['harga']; 
        $subtotal     = $harga_satuan * $qty; 
 
        mysqli_query($koneksi, "INSERT INTO detail_transaksi (id_transaksi, id_barang, jumlah, 
harga_satuan, subtotal)  
                                VALUES ('$id_transaksi_baru', '$id_b', '$qty', '$harga_satuan', 
'$subtotal')"); 
    } 
 
    echo "<script>alert('Transaksi berhasil disimpan! Kode Struk: $kode_struk'); 
window.location='detail_transaksi.php';</script>"; 
    exit; 
} 
?> 
<!DOCTYPE html> 
<html> 
 
<head> 
    <title>Transaksi Baru</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
rel="stylesheet"> 
</head> 
 
<body class="bg-light"> 
 
    <div class="container"> 
        <h4 class="mb-3">Buat Transaksi Baru</h4> 
 
        <div class="card shadow-sm"> 
            <div class="card-body"> 
                <form method="POST"> 
                    <!-- BARIS IDENTITAS TRANSAKSI (4 KOLOM) --> 
                    <div class="row mb-3"> 
                        <div class="col-md-3"> 
                            <label class="form-label fw-bold">Kode Struk</label> 
                            <input type="text" name="kode_struk" class="form-control fw-bold 
text-primary bg-light" value="<?= $kode_struk; ?>" readonly> 
                        </div> 
                        <div class="col-md-3"> 
                            <label class="form-label fw-bold">Nama Pelanggan</label> 
                            <input type="text" name="nama_pelanggan" class="form-control" 
placeholder="Masukkan nama pelanggan..." required autofocus> 
                        </div> 
                        <div class="col-md-3"> 
                            <label class="form-label fw-bold">Kasir / User</label> 
                            <input type="text" class="form-control bg-light" value="<?= 
$_SESSION['nama']; ?>" readonly> 
                        </div> 
                        <div class="col-md-3"> 
                            <label class="form-label fw-bold">Tanggal</label> 
                            <input type="text" class="form-control bg-light" value="<?= date('d
m-Y'); ?>" readonly> 
                        </div> 
                    </div> 
 
                    <!-- TABEL INPUT BARANG --> 
                    <table class="table table-bordered align-middle"> 
                        <thead class="table-light"> 
                            <tr> 
                                <th width="42%">Nama Barang</th> 
                                <th width="20%">Harga Satuan</th> 
                                <th width="13%">Jumlah</th> 
                                <th width="20%">Subtotal</th> 
                                <th width="5%">Aksi</th> 
                            </tr> 
                        </thead> 
                        <tbody id="baris-barang"> 
                            <tr> 
                                <td> 
                                    <select name="id_barang[]" class="form-select pilih-barang" 
onchange="hitungTotal()" required> 
                                        <option value="" data-harga="0">-- Pilih Barang -
</option> 
                                        <?php 
                                        $barang = mysqli_query($koneksi, "SELECT * FROM barang ORDER BY nama_barang ASC"); 
                                        while ($b = mysqli_fetch_assoc($barang)) { 
                                            echo "<option value='$b[id_barang]' dataharga='$b[harga]'>$b[nama_barang]</option>"; 
                                        } 
                                        ?> 
                                    </select> 
                                </td> 
                                <td> 
                                    <input type="text" class="form-control harga-satuan" value="0" readonly> 
                                </td> 
                                <td> 
                                    <input type="number" name="jumlah[]" class="form-control jumlah-beli" value="1" min="1" oninput="hitungTotal()" required> 
                                </td> 
                                <td> 
                                    <input type="text" class="form-control subtotal" value="0" readonly> 
                                </td> 
                                <td class="text-center"> 
                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusBaris(this)">X</button> 
                                </td> 
                            </tr> 
                        </tbody> 
                    </table> 
 
                    <button type="button" class="btn btn-outline-primary btn-sm mb-3" onclick="tambahBaris()">+ Tambah Baris Barang</button> 
 
                    <!-- BAGIAN TOTAL BAYAR & TOMBOL SIMPAN --> 
                    <div class="d-flex justify-content-between align-items-center border-top pt3"> 
                        <h5>Total Bayar: <span id="total-bayar" class="text-success fw-bold">Rp 
0</span></h5> 
                        <button type="submit" name="simpan_transaksi" class="btn btn-primary btn-lg">Simpan Transaksi</button> 
                    </div> 
                </form> 
            </div> 
        </div> 
    </div> 
 
    <!-- JAVASCRIPT SEDERHANA UNTUK HITUNG OTOMATIS & TAMBAH BARIS --> 
    <script> 
        // Fungsi mengubah angka menjadi format Rupiah (contoh: Rp 350.000) 
        function formatRupiah(angka) { 
            return "Rp " + angka.toLocaleString("id-ID"); 
        } 
 
        // Fungsi menghitung harga satuan, subtotal, dan total bayar 
        function hitungTotal() { 
            var semuaBaris = document.querySelectorAll("#baris-barang tr"); 
            var totalSemua = 0; 
 
            semuaBaris.forEach(function(baris) { 
                var dropdown = baris.querySelector(".pilih-barang"); 
                var harga = dropdown.options[dropdown.selectedIndex].getAttribute("data-harga") 
|| 0; 
                var jumlah = baris.querySelector(".jumlah-beli").value || 0; 
                var subtotal = harga * jumlah; 
 
                baris.querySelector(".harga-satuan").value = (harga > 0) ? formatRupiah(Number(harga)) : "0"; 
                baris.querySelector(".subtotal").value = (subtotal > 0) ? formatRupiah(subtotal) 
: "0"; 
 
                totalSemua = totalSemua + subtotal; 
            }); 
 
            document.getElementById("total-bayar").innerText = formatRupiah(totalSemua); 
        } 
 
        // Fungsi menambah baris barang baru 
        function tambahBaris() { 
            var tabel = document.getElementById("baris-barang"); 
            var barisBaru = tabel.rows[0].cloneNode(true); 
 
            // Reset isi baris baru ke kondisi awal 
            barisBaru.querySelector(".pilih-barang").selectedIndex = 0; 
            barisBaru.querySelector(".harga-satuan").value = "0"; 
            barisBaru.querySelector(".jumlah-beli").value = 1; 
            barisBaru.querySelector(".subtotal").value = "0"; 
 
            tabel.appendChild(barisBaru); 
        } 
 
        // Fungsi menghapus baris barang 
        function hapusBaris(tombol) { 
            var tabel = document.getElementById("baris-barang"); 
            if (tabel.rows.length > 1) { 
                tombol.closest("tr").remove(); 
                hitungTotal(); 
            } else { 
                alert("Minimal harus ada 1 baris barang!"); 
            } 
        } 
    </script> 
 
</body> 
 
</html>