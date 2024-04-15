<?php
session_start();
require_once 'db_connection.php';

// �������� �� ���� ���������
if ($_SESSION['role'] !== 'director') {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // ����������� ������
    $role = $_POST['role'];

    // �������������� ������ ��� ������� ������ ������������
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);

    if ($stmt->execute()) {
        
       header("Location: director_dashboard.php"); // ��� �������� ���������� ��������������
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>