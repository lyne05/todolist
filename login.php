<?php
session_start();
include('conn.php'); // File koneksi database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek pengguna di database
    $stmt = $conn->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verifikasi password (diasumsikan password di-hash)
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: index.php'); // Redirect ke halaman utama
        } else {
            echo 'Password salah!';
        }
    } else {
        echo 'Pengguna tidak ditemukan!';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel='stylesheet' href='css/bootstrap.min.css'>
    <title>Login</title>
</head>
<style>
    /* CSS untuk halaman login */

body {
    font-family: 'Comic Sans MS', cursive, sans-serif;
    background-color: #f0f8ff;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.container {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #333333;
    text-align: center;
}

.form-group {
    margin-bottom: 15px;
}

label {
    color: #666666;
}

.form-control {
    border-radius: 5px;
    border: 1px solid #cccccc;
    padding: 10px;
}

.btn-primary {
    background-color: #ff69b4; /* Warna pink cerah */
    border-color: #ff69b4;
    color: #ffffff;
    border-radius: 5px;
    transition: background-color 0.3s ease-in-out, transform 0.3s ease-in-out;
}

.btn-primary:hover {
    background-color: #ff1493; /* Warna pink lebih gelap */
    transform: scale(1.05);
}

a {
    color: #ff69b4;
}

a:hover {
    text-decoration: none;
}
</style>
<body>
<div class='container'>
    <h2>Login</h2>
    <form action='login.php' method='post'>
        <div class='form-group'>
            <label for='username'>Username:</label>
            <input type='text' class='form-control' id='username' name='username' required>
        </div>
        <div class='form-group'>
            <label for='password'>Password:</label>
            <input type='password' class='form-control' id='password' name='password' required>
        </div>
        <button type='submit' class='btn btn-primary'>Login</button>
    </form>
    <p>Belum punya akun? <a href='register.php'>Daftar di sini</a>.</p>
</div>
</body>
</html>