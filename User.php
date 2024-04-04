<?php
require_once 'db_connection.php';

class User {
  protected $id;
  protected $username;
  protected $role;

  public function __construct($id, $username, $role) {
    $this->id = $id;
    $this->username = $username;
    $this->role = $role;
  }
}

class Worker extends User {
  public function viewTasks() {
    global $conn;
    $sql = "SELECT * FROM tasks WHERE assigned_to = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $this->id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
  }

  public function startTask($taskId) {
    global $conn;
    $sql = "UPDATE tasks SET status = 'in_progress' WHERE id = ? AND assigned_to = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $taskId, $this->id);
    $stmt->execute();
  }

  public function markTaskDone($taskId) {
    global $conn;
    $sql = "UPDATE tasks SET status = 'done' WHERE id = ? AND assigned_to = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $taskId, $this->id);
    $stmt->execute();
  }
}

class Manager extends Worker {
  
  public function viewWorkers($role = 'worker') {
    global $conn;
    $sql = "SELECT * FROM users WHERE role = ?";
    $stmt = $conn->prepare($sql);
    // Привязываем параметр $role к SQL запросу
    $stmt->bind_param('s', $role);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
  }

  public function editTask($taskId, $data) {
    global $conn;
    $sql = "UPDATE tasks SET title = ?, description = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $data['title'], $data['description'], $taskId);
    $stmt->execute();
}

  public function assignTask($taskId, $workerId) {
    global $conn;
    $sql = "UPDATE tasks SET assigned_to = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $workerId, $taskId);
    $stmt->execute();
  }
}

class Director extends Manager {
  public function editWorker($workerId, $data) {
    global $conn;
    $sql = "UPDATE users SET username = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $data['username'], $workerId);
    $stmt->execute();
  }

  public function addTask($data) {
    global $conn;
    $sql = "INSERT INTO tasks (title, description, created_by) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $data['title'], $data['description'], $this->id);
    $stmt->execute();
  }
}