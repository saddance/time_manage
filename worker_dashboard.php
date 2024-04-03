<?php
session_start();
require_once 'db_connection.php';
require_once 'User.php';

// Check if the user is logged in and has the worker role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'worker') {
    header("Location: login.html");
    exit();
}

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

$worker = new Worker($userId, $username, $role);
$tasks = $worker->viewTasks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Dashboard - Time Tracking App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Time Tracking App</h1>
        <nav>
            <ul>
                <li><a href="worker_dashboard.php">Dashboard</a></li>
                <li><a href="worker_tasks.php">Tasks</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Welcome, <?php echo $username; ?>!</h2>
        <h3>Your Tasks:</h3>
        <?php if (count($tasks) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td><?php echo $task['title']; ?></td>
                            <td><?php echo $task['description']; ?></td>
                            <td><?php echo $task['status']; ?></td>
                            <td>
                                <?php if ($task['status'] === 'pending'): ?>
                                    <form action="worker_dashboard.php" method="POST">
                                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                        <input type="submit" name="start_task" value="Start Task">
                                    </form>
                                <?php elseif ($task['status'] === 'in_progress'): ?>
                                    <form action="worker_dashboard.php" method="POST">
                                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                        <input type="submit" name="mark_done" value="Mark as Done">
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No tasks assigned to you.</p>
        <?php endif; ?>
    </main>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['start_task'])) {
        $taskId = $_POST['task_id'];
        $worker->startTask($taskId);
        header("Location: worker_dashboard.php");
        exit();
    } elseif (isset($_POST['mark_done'])) {
        $taskId = $_POST['task_id'];
        $worker->markTaskDone($taskId);
        header("Location: worker_dashboard.php");
        exit();
    }
}
?>