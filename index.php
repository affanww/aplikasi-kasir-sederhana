<?php include "connect.php";
include "header.php";

//tambah barang 
if (isset($_POST['tambah'])) { 
    $nama_barang = $_POST['nama_barang'];
    $harga = $_POST['harga'];
   mysqli_query($koneksi, "INSERT INTO barang (nama_barang, harga) VALUES ('$nama_barang', '$harga')"); 
    header("Location: index.php"); 
} 
 
// EDIT BARANG 
if (isset($_POST['update'])) { 
    $id_barang   = $_POST['id_barang']; 
    $nama_barang = $_POST['nama_barang'];
    $harga = $_POST['harga'] ;
    mysqli_query($koneksi, "UPDATE barang SET nama_barang='$nama_barang', harga='$harga' WHERE 
id_barang='$id_barang'"); 
    header("Location: index.php"); 
} 
// 3. HAPUS BARANG 
if (isset($_GET['hapus'])) { 
    $id_barang = $_GET['hapus']; 
 
    try { 
        mysqli_query($koneksi, "DELETE FROM barang WHERE id_barang='$id_barang'"); 
        echo "<script>alert('Barang berhasil dihapus!'); window.location='index.php';</script>"; 
    } catch (mysqli_sql_exception $e) { 
        echo "<script>alert('Gagal menghapus! Barang ini sudah pernah digunakan dalam transaksi.'); 
window.location='index.php';</script>"; 
    } 
    exit; 
} 
?>
<div class="d-flex justify-content-between align-items-center mb-3"> 
    <h4>Manajemen Data Barang</h4> 
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah Barang</button> 
</div> 
 
<div class="card shadow-sm"> 
    <div class="card-body"> 
        <table class="table table-bordered table-striped align-middle"> 
            <thead class="table-dark"> 
                <tr> 
                    <th width="5%">No</th> 
                    <th>Nama Barang</th> 
                    <th>Harga</th>
                    <th width="18%">Aksi</th> 
                </tr> 
            </thead> 
            <tbody> 
                <?php 
                $no = 1; 
                $data = mysqli_query($koneksi, "SELECT * FROM barang"); 
                while ($d = mysqli_fetch_assoc($data)) { 
                ?> 
                    <tr> 
                        <td><?= $no++; ?></td> 
                        <td><?= $d['nama_barang']; ?></td> 
                        <td>Rp <?= number_format($d['harga'], 0, ',', '.') ?></td>

                        <td> 
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit<?= $d['id_barang']; ?>" >Edit</button> 
                            <a href="index.php?hapus=<?= $d['id_barang']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus barang ini?')">Hapus</a> 
                        </td> 
                    </tr> 
            </tbody> 
        <?php } ?> 
        </table> 
        </div>
</div> 
<!-- Modal Edit --> 
<?php 
$data_edit = mysqli_query($koneksi, "SELECT * FROM barang"); 
while ($d = mysqli_fetch_assoc($data_edit)) { 
?> 
    <div class="modal fade" id="edit<?= $d['id_barang']; ?>" tabindex="-1"> 
        <div class="modal-dialog"> 
            <form method="POST" class="modal-content"> 
                <div class="modal-header"> 
                    <h5 class="modal-title">Edit Barang</h5> 
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button> 
                </div> 
                <div class="modal-body"> 
                    <input type="hidden" name="id_barang" value="<?= $d['id_barang']; ?>"> 
                    <div class="mb-3"> 
                        <label>Nama Barang</label> 
                        <input type="text" name="nama_barang" class="form-control" value="<?= $d['nama_barang']; ?>" required> 
                    </div> 
                    <div class="mb-3"> 
                        <label>Harga</label> 
                        <input type="text" name="harga" class="form-control" value="<?= $d['harga']; ?>" required> 
                    </div> 
                </div> 
                <div class="modal-footer"> 
                    <button type="button" class="btn btn-secondary" data-bs dismiss="modal">Batal</button> 
                    <button type="submit" name="update" class="btn btn-primary">Update</button> 
                </div> 
            </form> 
        </div> 
    </div> 
<?php } ?> 
 
<!-- Modal Tambah --> 
<div class="modal fade" id="modalTambah" tabindex="-1"> 
    <div class="modal-dialog"> 
        <form method="POST" class="modal-content"> 
            <div class="modal-header"> 
                <h5 class="modal-title">Tambah Barang Baru</h5> 
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button> 
            </div> 
            <div class="modal-body"> 
                <div class="mb-3"> 
                    <label class="form-label">Nama Barang</label> 
                    <input type="text" name="nama_barang" class="form-control" required> 
                </div>
                <div class="mb-3">
                    <label class="form-label">Harga</label>
                    <input type="text" name="harga" class="form-control" value="Rp." required>
            </div> 
             </div> 
            <div class="modal-footer"> 
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button> 
                <button type="submit" name="tambah" class="btn btn-success">Simpan</button> 
            </div> 
        </form> 
    </div> 
</div> 
 
</div> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> 
</body> 
 
</html> 
 
 
<!-- <?php 
 

 
?> -->