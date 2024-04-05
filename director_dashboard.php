<?php
session_start();
require_once 'db_connection.php';
require_once 'User.php';

// Check if the user is logged in and has the director role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'director') {
    header("Location: login.html");
    exit();
}

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

$director = new Director($userId, $username, $role);
$workers = $director->viewWorkers('worker');
$managers = $director->viewWorkers('manager');
$tasks = $director->viewTasks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Director Dashboard - Time Tracking App</title>
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
    <h2>Welcome, <?php echo $username; ?>! <a href="edit_user.php?id=<?php echo $userId; ?>" class="edit-btn">Edit</a></h2>
        
        <h3>Workers:</h3>
        <?php if (count($workers) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($workers as $worker): ?>
                        <tr>
                            <td><?php echo $worker['username']; ?></td>
                            <td><?php echo $worker['role']; ?></td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $worker['id']; ?>">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No workers found.</p>
        <?php endif; ?>

        <h3>Managers:</h3>
        <?php if (count($managers) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($managers as $manager): ?>
                        <tr>
                            <td><?php echo $manager['username']; ?></td>
                            <td><?php echo $manager['role']; ?></td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $manager['id']; ?>">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No managers found.</p>
        <?php endif; ?>

        

        <h3>Add Task:</h3>
        <form action="add_task_process.php" method="POST">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required><br>

            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea><br>

            <label for="assigned_to">Assign to:</label>
            <select name="assigned_to" id="assigned_to" required>
                <?php foreach ($workers as $worker): ?>
                    <option value="<?php echo $worker['id']; ?>"><?php echo $worker['username']; ?></option>
                <?php endforeach; ?>
            </select><br>

            <label for="observer">Observer (Manager):</label>
    <select name="observer" id="observer">
        <option value="">Select a manager</option>
        <?php foreach ($managers as $manager): ?>
            <option value="<?php echo $manager['id']; ?>"><?php echo $manager['username']; ?></option>
        <?php endforeach; ?>
    </select><br>
    
            <input type="submit" value="Add Task">
        </form>
    </main>
</body>
</html>