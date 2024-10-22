<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    exit('Not logged in');
}

$conn = new mysqli("localhost", "root", "", "db_task");
if ($conn->connect_error) {
    exit('Connection failed: ' . $conn->connect_error);
}

if(isset($_POST['add'])){
    $user_id = $_SESSION['user_id'];
    $task = $_POST['task'];
    $category = $_POST['category'];
    
    $stmt = $conn->prepare("INSERT INTO task (task, user_id, category) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $task, $user_id, $category);
    
    if($stmt->execute()){
        $_SESSION['notification'] = "Task added successfully!";
    } else {
        $_SESSION['notification'] = "Error: " . $stmt->error;
    }
    
    $stmt->close();
}
$conn->close();

header("Location: index.php");
exit();
?>