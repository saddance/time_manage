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
$tasks = $worker->viewallTasks();
?>

<?php include 'menu.php'; ?>  <!-- Подключение меню -->
    <section class="tasks">
        <h2>Your Tasks</h2>
        <?php if (count($tasks) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td><?php echo $task['title']; ?></td>
                            <td><?php echo $task['description']; ?></td>
                            <td><?php echo $task['status']; ?></td>
                            
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No tasks assigned to you.</p>
        <?php endif; ?>
    </section>
    </div>
    </main>
</body>
</html>