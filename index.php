<?php
require_once 'User.php';
require_once 'Task.php';

session_start();

// Simulating user login (replace with proper authentication)
$userId = 1;
$username = 'john_doe';
$role = 'worker';

$user = new Worker($userId, $username, $role);

if ($role === 'worker') {
  $tasks = $user->viewTasks();
  foreach ($tasks as $task) {
    echo "Task: " . $task['title'] . " - Status: " . $task['status'] . "<br>";
  }

  // Starting a task
  $user->startTask(1);

  // Marking a task as done
  $user->markTaskDone(2);
} elseif ($role === 'manager') {
  $user = new Manager($userId, $username, $role);
  $workers = $user->viewWorkers();
  foreach ($workers as $worker) {
    echo "Worker: " . $worker['username'] . "<br>";
  }

  // Editing a task
  $user->editTask(1, ['title' => 'Updated Task', 'description' => 'Updated description']);

  // Assigning a task to a worker
  $user->assignTask(1, 2);
} elseif ($role === 'director') {
  $user = new Director($userId, $username, $role);

  // Editing a worker
  $user->editWorker(2, ['username' => 'updated_username']);

  // Adding a new task
  $user->addTask(['title' => 'New Task', 'description' => 'New task description']);
}
?>