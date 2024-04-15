<?php
session_start();
require_once 'db_connection.php';

// ѕроверка на роль директора
if ($_SESSION['role'] !== 'director') {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // ’еширование парол€
    $role = $_POST['role'];

    // ѕодготовленный запрос дл€ вставки нового пользовател€
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);

    if ($stmt->execute()) {
        
       header("Location: director_dashboard.php"); // или страница управлени€ пользовател€ми
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>