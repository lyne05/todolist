<?php
session_start();
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'db_task';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
    $stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
    $stmt->bind_param('sss', $user, $email, $hashed_pass);
    if ($stmt->execute()) {
        header('Location: login.php');
    } else {
        echo 'Registration failed!';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel='stylesheet' href='css/bootstrap.min.css'>
    <title>Register</title>
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
<h2>Register</h2>
<form action='register.php' method='post'>
    <div class='form-group'>
        <label for='username'>Username:</label>
        <input type='text' class='form-control' id='username' name='username' required>
    </div>
    <div class='form-group'>
        <label for='email'>Email:</label>
        <input type='email' class='form-control' id='email' name='email' required>
    </div>
    <div class='form-group'>
        <label for='password'>Password:</label>
        <input type='password' class='form-control' id='password' name='password' required>
    </div>
    <button type='submit' class='btn btn-primary'>Register</button>
</form>
</div>
</body>
</html>