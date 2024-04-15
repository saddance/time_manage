<?php
session_start();
require_once 'db_connection.php';

// Проверка прав администратора (директора)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'director') {
    header("Location: login.html");
    exit();
}

if (isset($_GET['id'])) {
    $taskId = $_GET['id'];

    // Подготовленный запрос для удаления задачи
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $taskId);

    if ($stmt->execute()) {
        // Перенаправление обратно на страницу задач с сообщением об успехе
        $_SESSION['message'] = "Task deleted successfully.";
        header("Location: director_tasks.php");
    } else {
        // Обработка ошибки
        $_SESSION['error'] = "Error deleting task.";
        header("Location: director_tasks.php");
    }
} else {
    // Если ID задачи не передан, перенаправить обратно
    header("Location: director_tasks.php");
}

?>