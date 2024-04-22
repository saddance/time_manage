<?php
session_start();
require_once 'db_connection.php';

// Проверка, что пользователь авторизован
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Получаем ID задачи из GET-параметра
$taskId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Получаем информацию о задаче из базы данных
if ($taskId > 0) {
    $sql = "SELECT * FROM tasks WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $taskId);
    $stmt->execute();
    $result = $stmt->get_result();
    $task = $result->fetch_assoc();
}

// Проверяем, нашлась ли задача
if (!$task) {
    echo "Задача не найдена.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Детали задачи</title>
    <!-- Стили и прочее -->
</head>
<body>
    <h1>Детали задачи: <?php echo htmlspecialchars($task['title']); ?></h1>
    <p>Описание: <?php echo htmlspecialchars($task['description']); ?></p>
    <!-- Вывод других деталей задачи -->
    <p>Статус: <?php echo htmlspecialchars($task['status']); ?></p>
    <p>Дата начала:<?php echo htmlspecialchars($task['start_date'] ? date('d.m.Y H:i', strtotime($task['start_date'])): 'Не указано'); ?></p>
    <p>Дата окончания:<?php echo htmlspecialchars($task['due_date'] ? date('d.m.Y H:i', strtotime($task['due_date'])): 'Не указано'); ?></p>
   
    <p>Принято в работу:<?php echo $task['accepted_date'] ? date('d.m.Y H:i', strtotime($task['accepted_date'])) : 'Не принято'; ?></p>
    <p>Завершено:<?php echo $task['completed_date'] ? date('d.m.Y H:i', strtotime($task['completed_date'])) : 'Не завершено'; ?></p>
</body>
</html>
