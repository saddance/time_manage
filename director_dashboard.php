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

<?php include 'menu.php'; ?>  <!-- Подключение меню -->
        
    <section class="tasks">

    <h3>Создать задание:</h3>
<form action="add_task_process.php" method="POST">
    <label for="title">Заголовок:</label>
    <input type="text" name="title" id="title" required><br>

    <label for="description">Описание:</label>
    <textarea name="description" id="description" required></textarea><br>

    <label for="start_date">Дата начала:</label>
    <input type="date" name="start_date" id="start_date" required><br>

    <label for="due_date">Дата окончания:</label>
    <input type="date" name="due_date" id="due_date" required><br>

    <label for="assigned_to">Выполняющий:</label>
    <select name="assigned_to" id="assigned_to" required>
        <?php foreach ($workers as $worker): ?>
            <option value="<?php echo $worker['id']; ?>"><?php echo $worker['username']; ?></option>
        <?php endforeach; ?>
    </select><br>

    <label for="observer">Проверяющий (Менеджер):</label>
    <select name="observer" id="observer" required>
        <option value="">Выбрать менеджера</option>
        <?php foreach ($managers as $manager): ?>
            <option value="<?php echo $manager['id']; ?>"><?php echo $manager['username']; ?></option>
        <?php endforeach; ?>
    </select><br>

    <input type="submit" value="Add Task">
</form>
        <h3>Добавить сотрудника:</h3>
    <form action="add_worker_process.php" method="POST">
        <label for="username">ФИО:</label>
        <input type="text" name="username" id="username" required><br>

        <label for="password">Пароль:</label>
        <input type="password" name="password" id="password" required><br>

        <label for="role">Роль:</label>
        <select name="role" id="role" required>
            <option value="worker">Мастер производства</option>
            <option value="manager">Менеджер</option>
        </select><br>
    
        <input type="submit" value="Add Worker">
    </form>
    </section>
    </main>

</body>
</html>