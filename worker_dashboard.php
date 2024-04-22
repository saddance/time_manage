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
$currentDate = date('Y-m-d'); // Текущая дата в формате 'YYYY-MM-DD'

?>
<?php include 'menu.php'; ?>  <!-- Подключение меню -->
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Time Tracking App</title>
    <link rel="stylesheet" href="worker_style.css">
</head>
<body>
    <header>
      
    </header>
    <main>
    <section class="tasks">
            <h3>Your Tasks:</h3>
            <?php if (!empty($tasks)): ?>
                <div class="tasks-container">
                    <?php foreach ($tasks as $task): ?>
                        
                        <div class="task-card">

                        <?php
        $status = $task['status']; // получаем статус из базы данных
        if ($status === 'pending') {
            $status = 'Не начато'; // заменяем 'pending' на 'активно'
        } elseif ($status === 'in_progress') {
            $status = 'В процессе'; // или устанавливаем статус в 'просрочено', если задача просрочена
        } elseif ($currentDate > $task['due_date']) {
            $status = 'Просрочено'; // или устанавливаем статус в 'просрочено', если задача просрочена
        } 
        
        ?>
                        <div class="task-content">
                           <div class="title"> <?php echo htmlspecialchars($task['title']); ?></div>
                           <div class="description"><p><?php echo htmlspecialchars($task['description']); ?></p></div>
                        </div>
                        <div class="task-info">
                            <?php
                    // Проверяем, не просрочена ли задача
                    if ($currentDate > $task['due_date']) {
                        echo "<p>Просрочено</p>";
                    } else {
                        echo "<p>" . htmlspecialchars($status) . "</p>";
                    }
                    ?>
                            <div class="task-dates">
                            <p><?php echo htmlspecialchars($task['start_date']); ?></p>
                            <p><?php echo htmlspecialchars($task['due_date']); ?></p>
                            </div>
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
                            <?php  if (isset($_POST['start_task'])) {
                                 $taskId = $_POST['task_id'];
                                    $currentDateTime = date('Y-m-d H:i:s'); // Текущие дата и время
                                    $sql = "UPDATE tasks SET status = 'in_progress', accepted_date = ? WHERE id = ? AND status = 'pending'";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param('si', $currentDateTime, $taskId);
                                    $stmt->execute();
                                    // Проверка выполнения запроса и редирект или вывод сообщения
                                }
                            ?>
                           <?php if (isset($_POST['mark_done'])) {
                                $taskId = $_POST['task_id'];
                                $currentDateTime = date('Y-m-d H:i:s'); // Текущие дата и время
                                $sql = "UPDATE tasks SET status = 'completed', completed_date = ? WHERE id = ? AND status = 'in_progress'";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param('si', $currentDateTime, $taskId);
                                $stmt->execute();
                                // Проверка выполнения запроса и редирект или вывод сообщения
                                }?>
                                </div>
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

