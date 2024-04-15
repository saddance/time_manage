<?php
session_start();
require_once 'db_connection.php';

// �������� ���� �������������� (���������)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'director') {
    header("Location: login.html");
    exit();
}

if (isset($_GET['id'])) {
    $taskId = $_GET['id'];

    // �������������� ������ ��� �������� ������
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $taskId);

    if ($stmt->execute()) {
        // ��������������� ������� �� �������� ����� � ���������� �� ������
        $_SESSION['message'] = "Task deleted successfully.";
        header("Location: director_tasks.php");
    } else {
        // ��������� ������
        $_SESSION['error'] = "Error deleting task.";
        header("Location: director_tasks.php");
    }
} else {
    // ���� ID ������ �� �������, ������������� �������
    header("Location: director_tasks.php");
}

?>