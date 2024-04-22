<?php
session_start();
require_once 'db_connection.php';
require_once 'User.php';

// Check if the user is logged in and has the worker role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'worker') {
    header("Location: login.html");
    exit();
}

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

$worker = new Worker($userId, $username, $role);
$tasks = $worker->viewallTasks();
?>

<?php include 'menu.php'; ?>  <!-- Подключение меню -->
<?php include 'user_tasks.php'; ?> 
    </div>
    </main>
</body>
</html>