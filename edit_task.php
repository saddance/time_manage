<?php
session_start();
require_once 'db_connection.php';
require_once 'User.php';

// Check if the user is logged in and has the manager role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.html");
    exit();
}

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

$manager = new Manager($userId, $username, $role);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = $_POST['task_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    $manager->editTask($taskId, ['title' => $title, 'description' => $description]);
    header("Location: manager_tasks.php");
    exit();
}

$taskId = $_GET['id'];
$task = $manager->getTaskById($taskId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task - Time Tracking App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Time Tracking App</h1>
        <nav>
            <ul>
                <li><a href="manager_dashboard.php">Dashboard</a></li>
                <li><a href="manager_tasks.php">Tasks</a></li>
                <li><a href="manager_workers.php">Workers</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Edit Task</h2>
        <form action="edit_task.php" method="POST">
            <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" value="<?php echo $task['title']; ?>" required><br>

            <label for="description">Description:</label>
            <textarea name="description" id="description" required><?php echo $task['description']; ?></textarea><br>

            <input type="submit" value="Save">
        </form>
    </main>
</body>
</html>