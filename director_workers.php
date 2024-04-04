<?php
session_start();
require_once 'db_connection.php';
require_once 'User.php';

// Проверка роли пользователя
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'director') {
    header("Location: login.html");
    exit();
}

$director = new Director($_SESSION['user_id'], $_SESSION['username'], $_SESSION['role']);
$workers = $director->viewWorkers('worker');
$managers = $director->viewWorkers('manager');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Director Workers - Time Tracking App</title>
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
        <h2>Workers and Managers:</h2>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_merge($workers, $managers) as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo htmlspecialchars($user['id']); ?>">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>