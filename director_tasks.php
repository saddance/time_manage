<?php
session_start();
require_once 'db_connection.php';
require_once 'User.php';
// ��������, ��� ������������ ����� ��� ��������
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'director') {
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
<?php include 'user_tasks.php'; ?> 
    </main>
</body>
</html>