<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'director') {
    header("Location: login.html");
    exit();
}

if (isset($_GET['id'])) {
    $workerId = $_GET['id'];

    // Дополнительная проверка на наличие связанных задач перед удалением может быть здесь

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $workerId);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Worker deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting worker.";
    }
}

header("Location: director_workers.php");
?>