<?php
session_start();
require_once 'db_connection.php';
require_once 'User.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['start_task'])) {
        $taskId = $_POST['task_id'];
        $worker = new Worker($_SESSION['user_id'], $_SESSION['username'], $_SESSION['role']);
        $worker->startTask($taskId);
    } elseif (isset($_POST['mark_done'])) {
        $taskId = $_POST['task_id'];
        $worker = new Worker($_SESSION['user_id'], $_SESSION['username'], $_SESSION['role']);
        $worker->markTaskDone($taskId);
    }
}
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
<?php include 'menu.php'; ?>  <!-- Подключение меню -->

    <section class="tasks">
            <h3>Your Tasks:</h3>
            <?php if (!empty($tasks)): ?>
                <div class="tasks-container">
                    <?php foreach ($tasks as $task): ?>
                        <div class="task-card">
                            <h4><?php echo htmlspecialchars($task['title']); ?></h4>
                            <p><?php echo htmlspecialchars($task['description']); ?></p>
                            <p>Status: <?php echo htmlspecialchars($task['status']); ?></p>
                            <p><?php echo htmlspecialchars($task['start_date']); ?></p>
                            <p><?php echo htmlspecialchars($task['due_date']); ?></p>
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
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No tasks assigned to you.</p>
            <?php endif; ?>
        </section>
</div>
    </main>
</body>

