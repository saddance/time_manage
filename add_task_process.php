<?php
session_start();
require_once 'db_connection.php';
require_once 'User.php';

// Check if the user is logged in and has the director role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'director' || $_SESSION['role'] !== 'manager') {
    header("Location: login.html");
    exit();
}

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

if ($role === 'director') {
    $editor = new Director($userId, $username, $role);
} else {
    $editor = new Manager($userId, $username, $role);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    $editor->addTask(['title' => $title, 'description' => $description]);
    header("Location: manager_dashboard.php");
    exit();
}
?>