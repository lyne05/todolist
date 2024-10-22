<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "db_task");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Edit Profile</title>
    <style>
        body {
            font-family: 'Comic Sans MS', cursive, sans-serif;
            font-size: medium;
            background-color: #f0f8ff;
            padding: 20px;
        }
        .well {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h3 {
            color: #333333;
            text-align: center;
        }
        .form-control {
            border-radius: 5px;
            border: 1px solid #cccccc;
            padding: 10px;
            margin-bottom: 10px;
        }
        .btn-primary {
            background-color: #ff69b4;
            border-color: #ff69b4;
            color: #ffffff;
            border-radius: 5px;
            transition: background-color 0.3s ease-in-out, transform 0.3s ease-in-out;
        }
        .btn-primary:hover {
            background-color: #ff1493;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="well">
                    <h3>Edit Profile</h3>
                    <form method="POST" action="update_profile.php">
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required/>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required/>
                        </div>
                        <div class="form-group">
                            <label for="password">New Password (leave blank to keep current):</label>
                            <input type="password" id="password" name="password" class="form-control"/>
                        </div>
                        <button type="submit" class="btn btn-primary" name="update">Update Profile</button>
                    </form>
                    <br>
                    <a href="index.php" class="btn btn-secondary">Back to To-Do List</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>