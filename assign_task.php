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
    $workerId = $_POST['worker_id'];

    $manager->assignTask($taskId, $workerId);
    header("Location: manager_tasks.php");
    exit();
}

$taskId = $_GET['id'];
$workers = $manager->viewWorkers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Task - Time Tracking App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
    <div class="header"><h1>Сервис по учету рабочего времени</h1></div>
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
        <h2>Assign Task</h2>
        <form action="assign_task.php" method="POST">
            <input type="hidden" name="task_id" value="<?php echo $taskId; ?>">
            <label for="worker_id">Assign to:</label>
            <select name="worker_id" id="worker_id" required>
                <?php foreach ($workers as $worker): ?>
                    <option value="<?php echo $worker['id']; ?>"><?php echo $worker['username']; ?></option>
                <?php endforeach; ?>
            </select><br>

            <input type="submit" value="Assign">
        </form>
    </main>
</body>
</html>