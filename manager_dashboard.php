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

<?php include 'menu.php'; ?>  <!-- Подключение меню -->
    <section class="tasks">
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

            <label for="assigned_to">Assign to:</label>
            <select name="assigned_to" id="assigned_to" required>
                <?php foreach ($workers as $worker): ?>
                    <option value="<?php echo $worker['id']; ?>"><?php echo $worker['username']; ?></option>
                <?php endforeach; ?>
            </select><br>

            <input type="submit" value="Add Task">
        </form>
        
    
        </section>
    </main>
</body>
</html>