<?php
session_start();
require_once 'db_connection.php';
require_once 'User.php';

// Проверка, что пользователь вошел как директор
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.html");
    exit();
}

// Проверяем, есть ли имя пользователя в сессии, и если нет, устанавливаем его как "Guest" или перенаправляем на страницу входа.
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    $username = "Guest"; // Или используйте редирект на страницу входа.
}
$manager = new Manager($_SESSION['user_id'], $_SESSION['username'], $_SESSION['role']);
$workers = $manager->viewWorkers('worker');
?>

<?php include 'menu.php'; ?>  <!-- Подключение меню -->
    <section class="tasks">
        <h2>Workers :</h2>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Role</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_merge($workers) as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        
                <?php endforeach; ?>
            </tbody>
        </table>
        </section>
    </main>
</body>
</html>