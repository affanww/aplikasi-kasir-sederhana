<?php 
session_start(); 
include 'connect.php'; 
 
if (isset($_POST['login'])) { 
    $username = $_POST['username']; 
    $password = md5($_POST['password']); 
 
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND password='$password'"); 
    $cek   = mysqli_num_rows($query); 
 
    if ($cek > 0) { 
        $data = mysqli_fetch_assoc($query); 
        $_SESSION['id_user'] = $data['id_user']; 
        $_SESSION['nama']    = $data['nama']; 
        header("Location: index.php"); 
        exit; 
    } else { 
        $pesan = "Username atau Password salah!"; 
    } 
} 
?> 
<!DOCTYPE html> 
<html> 
<head> 
    <title>Login Kasir</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
rel="stylesheet"> 
</head> 
<body class="bg-light"> 
<div class="container mt-5"> 
    <div class="row justify-content-center"> 
        <div class="col-md-4"> 
            <div class="card shadow"> 
                <div class="card-body p-4"> 
                    <h4 class="text-center mb-3">Login Aplikasi Kasir</h4> 
                    <?php if (isset($pesan)) { ?> 
                        <div class="alert alert-danger"><?= $pesan; ?></div> 
                    <?php } ?> 
                    <form method="POST"> 
                        <div class="mb-3"> 
                            <label class="form-label">Username</label> 
                            <input type="text" name="username" class="form-control" required autofocus> 
                        </div> 
                        <div class="mb-3"> 
                            <label class="form-label">Password</label> 
                            <input type="password" name="password" class="form-control" required> 
                        </div> 
                        <button type="submit" name="login" class="btn btn-primary w100">Login</button> 
                    </form> 
                </div> 
            </div> 
        </div> 
    </div> 
</div> 
</body> 
</html>