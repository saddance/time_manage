<?php
session_start();
require_once 'db_connection.php';

// ѕроверка, что пользователь вошел как директор
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'director') {
    header("Location: login.html");
    exit();
}

// ѕолучаем все задачи, назначенные работникам
$sql = "SELECT tasks.id, tasks.title, tasks.description, tasks.status, users.username AS assigned_to 
        FROM tasks
        JOIN users ON tasks.assigned_to = users.id";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$tasks = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Director Tasks - Time Tracking App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
        <h1>Time Tracking App</h1>
        <nav>
            <ul>
                <li><a href="director_dashboard.php">Dashboard</a></li>
                <li><a href="director_tasks.php">Tasks</a></li>
                <li><a href="director_workers.php">Workers</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>All Assigned Tasks:</h2>
        <?php if (!empty($tasks)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($task['title']); ?></td>
                            <td><?php echo htmlspecialchars($task['description']); ?></td>
                            <td><?php echo htmlspecialchars($task['status']); ?></td>
                            <td><?php echo htmlspecialchars($task['assigned_to']); ?></td>
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