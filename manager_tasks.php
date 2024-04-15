<?php
session_start();
require_once 'db_connection.php';
require_once 'User.php';
// ��������, ��� ������������ ����� ��� ��������
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.html");
    exit();
}

// ���������, ���� �� ��� ������������ � ������, � ���� ���, ������������� ��� ��� "Guest" ��� �������������� �� �������� �����.
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    $username = "Guest"; // ��� ����������� �������� �� �������� �����.
}
// �������� ��� ������, ����������� ����������
$sql = "SELECT tasks.id, tasks.title, tasks.description, tasks.status, 
                assigned.username AS assigned_to, observer.username AS observer
                FROM tasks
                JOIN users AS assigned ON tasks.assigned_to = assigned.id
                LEFT JOIN users AS observer ON tasks.observer = observer.id
                ORDER BY tasks.id DESC;";
                
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$tasks = $result->fetch_all(MYSQLI_ASSOC);
?>

<?php include 'menu.php'; ?>  <!-- Подключение меню -->
    <section class="tasks">
        <h2>All Assigned Tasks:</h2>
        <?php if (!empty($tasks)): ?>
            <table>
                <thead>
                    <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Observer</th> <!-- �������� ������� ����������� -->
                    <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                        <td><?php echo htmlspecialchars($task['title']); ?></td>
                        <td><?php echo htmlspecialchars($task['description']); ?></td>
                        <td><?php echo htmlspecialchars($task['status']); ?></td>
                        <td><?php echo htmlspecialchars($task['assigned_to']); ?></td>
                        <td><?php echo htmlspecialchars($task['observer']); ?></td> <!-- ���������� ����������� ����������� -->
                        <td><a href="delete_task.php?id=<?php echo $task['id']; ?>" onclick="return confirm('Are you sure you want to delete this task?');">Delete</a></td>
                </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No tasks found.</p>
        <?php endif; ?>
        <?php if (isset($_SESSION['message'])): ?>
    <div class="message">
        <?php 
        echo $_SESSION['message']; 
        unset($_SESSION['message']);
        ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="error">
        <?php 
        echo $_SESSION['error']; 
        unset($_SESSION['error']);
        ?>
    </div>
<?php endif; ?>
</section>
    </main>
</body>
</html>