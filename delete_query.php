<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    exit('Not logged in');
}

$conn = new mysqli("localhost", "root", "", "db_task");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM task WHERE task_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $task_id, $user_id);

    if ($stmt->execute()) {
        $_SESSION['notification'] = "Task deleted successfully!";
    } else {
        $_SESSION['notification'] = "Failed to delete task. Please try again.";
    }

    $stmt->close();
}

header("Location: index.php");
exit();
?>
