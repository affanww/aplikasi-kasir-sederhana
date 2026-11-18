
<?php
require ('connect.php');
header('Content-Type: application/json');
// Query join untuk mengambil field yang diminta
$query = "SELECT detail_transaksi.jumlah, transaksi.kode_struk,
transaksi.nama_pelanggan, transaksi.tanggal_transaksi,
transaksi.total_bayar, barang.nama_barang, barang.harga FROM
detail_transaksi INNER JOIN transaksi ON
detail_transaksi.id_transaksi=transaksi.id_transaksi INNER JOIN barang ON
detail_transaksi.id_barang=barang.id_barang;";
$result = mysqli_query($koneksi, $query);
$data = array();
if ($result) {
while ($row = mysqli_fetch_assoc($result)) {
$data[] = $row;
}
}

echo json_encode($data);
?>