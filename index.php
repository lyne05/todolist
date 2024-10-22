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
$filter = $_GET['filter'] ?? 'all';
$search = $_GET['search'] ?? '';
$queryStr = "SELECT task_id, task, status, category FROM task WHERE user_id = ?";
$params = array($user_id);
$types = "i";

if ($filter === 'completed') {
    $queryStr .= " AND status = 'Done'";
} elseif ($filter === 'incomplete') {
    $queryStr .= " AND status != 'Done'";
}
if ($search) {
    $queryStr .= " AND task LIKE ?";
    $params[] = "%$search%";
    $types .= "s";
}
$queryStr .= " ORDER BY task_id ASC";

$stmt = $conn->prepare($queryStr);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1"/>
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
        select.form-control {
            font-size: small;
            color: #333;
            background-color: #fff;
            -webkit-appearance: menulist;
            -moz-appearance: menulist;
            appearance: menulist;
            padding: 5px 10px;
            height: auto;
            line-height: 1.5;
        }
        select.form-control option {
            font-size: small;
            color: #333;
            background-color: #fff;
            padding: 5px;
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
        a {
            color: #ff69b4;
        }
        a:hover {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
            <a class="navbar-brand" href="#">
                    <img src="images/logo.png" alt="Logo" style="display:inline-block; margin-right:5px; max-height:100%;">
                    To Do Wish App
            </a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="well">
                    <h3>User Profile</h3>
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username'] ?? ''); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
                    <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
                </div>
            </div>
            <div class="col-md-8">
                <div class="well">
                    <h3>To Do List</h3>
                    <form method="POST" class="form-inline" action="add_query.php">
                        <input type="text" class="form-control" name="task" required placeholder="Enter task"/>
                        <select name="category" class="form-control" required>
                            <option value="" disabled selected>Select Category</option>
                            <option value="Work">Work</option>
                            <option value="Personal">Personal</option>
                            <option value="Groceries">Groceries</option>
                        </select>
                        <button class="btn btn-primary" name="add">Add Task</button>
                    </form>
                    <br>
                    <form method="GET" action="">
                        <select name="filter" class="form-control" onchange="this.form.submit()">
                            <option value="all" <?php if ($filter == 'all') echo 'selected'; ?>>All Tasks</option>
                            <option value="completed" <?php if ($filter == 'completed') echo 'selected'; ?>>Completed Tasks</option>
                            <option value="incomplete" <?php if ($filter == 'incomplete') echo 'selected'; ?>>Incomplete Tasks</option>
                        </select>
                    </form>
                    <br>
                    <form method="GET" action="">
                        <input type="text" class="form-control" name="search" placeholder="Search Tasks"/>
                        <button class="btn btn-primary">Search</button>
                    </form>
                    <br>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Task</th>
                                <th>Status</th>
                                <th>Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $filter = $_GET['filter'] ?? 'all';
                        $search = $_GET['search'] ?? '';
                        $queryStr = "SELECT task_id, task, status, category FROM task WHERE user_id = ?";
                        $params = array($user_id);
                        $types = "i";

                        if ($filter === 'completed') {
                            $queryStr .= " AND status = 'Done'";
                        } elseif ($filter === 'incomplete') {
                            $queryStr .= " AND status != 'Done'";
                        }
                        if ($search) {
                            $queryStr .= " AND task LIKE ?";
                            $params[] = "%$search%";
                            $types .= "s";
                        }
                        $queryStr .= " ORDER BY task_id ASC";

                        $stmt = $conn->prepare($queryStr);
                        $stmt->bind_param($types, ...$params);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $count = 1;
                        while($fetch = $result->fetch_assoc()){
                        ?>
                        <?php if (isset($_SESSION['notification'])): ?>
                            <div class="alert alert-success" role="alert">
                            <?php 
                            echo $_SESSION['notification'];
                            unset($_SESSION['notification']); 
                            ?>
                            </div>
                        <?php endif; ?>
                        <tr>
                            <td><?php echo $count++?></td>
                            <td><?php echo htmlspecialchars($fetch['task'])?></td>
                            <td><?php echo htmlspecialchars($fetch['status'])?></td>
                            <td><?php echo htmlspecialchars($fetch['category'])?></td>
                            <td>
                                <?php
                                if($fetch['status'] != "Done"){
                                    echo '<a href="update_task.php?task_id='.$fetch['task_id'].'" class="btn btn-success btn-sm">Complete</a> ';
                                }
                                ?>
                                <a href="delete_query.php?task_id=<?php echo $fetch['task_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                        <?php
                        }
                        $stmt->close();
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>