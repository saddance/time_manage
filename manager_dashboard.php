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
$workers = $manager->viewWorkers();
$tasks = $manager->viewTasks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard - Time Tracking App</title>
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
        <h2>Welcome, <?php echo $username; ?>!</h2>
        <h3>Workers:</h3>
        <?php if (count($workers) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($workers as $worker): ?>
                        <tr>
                            <td><?php echo $worker['username']; ?></td>
                            <td><?php echo $worker['role']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No workers found.</p>
        <?php endif; ?>
        <h3>Add Task:</h3>
        <form action="add_task_process.php" method="POST">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required><br>

            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea><br>

            <input type="submit" value="Add Task">
        </form>
        <h3>Tasks:</h3>
        <?php if (count($tasks) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td><?php echo $task['title']; ?></td>
                            <td><?php echo $task['description']; ?></td>
                            <td><?php echo $task['status']; ?></td>
                            <td><?php echo $task['assigned_to']; ?></td>
                            <td>
                                <a href="edit_task.php?id=<?php echo $task['id']; ?>">Edit</a>
                                <a href="assign_task.php?id=<?php echo $task['id']; ?>">Assign</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No tasks found.</p>
        <?php endif; ?>
    </main>
</body>
</html>