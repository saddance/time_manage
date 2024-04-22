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
  public function viewallTasks() {
    global $conn;
    $sql = "SELECT * FROM tasks WHERE assigned_to = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $this->id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
  }
  public function viewTasks() {
    global $conn;
    // В запрос добавлено условие, которое исключает задания со статусом 'done'
    $sql = "SELECT id, title, description, status, start_date, due_date FROM tasks WHERE assigned_to = ? AND status <> 'done' ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $this->id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}
public function startTask($taskId) {
  global $conn;
  $sql = "UPDATE tasks SET status = 'in_progress', accepted_date = NOW() WHERE id = ? AND assigned_to = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ii', $taskId, $this->id);
  $stmt->execute();
}

public function markTaskDone($taskId) {
  global $conn;
  $sql = "UPDATE tasks SET status = 'done', completed_date = NOW() WHERE id = ? AND assigned_to = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ii', $taskId, $this->id);
  $stmt->execute();
}

  public function deleteTask($taskId) {
    global $conn;
    $sql = "DELETE FROM tasks WHERE id = ? AND assigned_to = ?";
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
    $stmt->bind_param('s', $role);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
  }
  public function addTask($data) {
    global $conn;
    $sql = "INSERT INTO tasks (title, description, assigned_to, observer, start_date, due_date) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssisss', $data['title'], $data['description'], $data['assigned_to'], $data['observer'], $data['start_date'], $data['due_date']);
    $stmt->execute();
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
}