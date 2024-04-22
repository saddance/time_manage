<?php
session_start();
require_once 'db_connection.php';
require_once 'User.php';

// Проверка, что пользователь вошел как директор
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'director') {
    header("Location: login.html");
    exit();
}

// Проверяем, есть ли имя пользователя в сессии, и если нет, устанавливаем его как "Guest" или перенаправляем на страницу входа.
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    $username = "Guest"; // Или используйте редирект на страницу входа.
}
$director = new Director($_SESSION['user_id'], $_SESSION['username'], $_SESSION['role']);
$workers = $director->viewWorkers('worker');
$managers = $director->viewWorkers('manager');
?>

<?php include 'menu.php'; ?>  <!-- Подключение меню -->
    <section class="tasks">
        <h2>Сотрудники:</h2>
        <table>
            <thead>
                <tr>
                    <th>ФИО</th>
                    <th>Роль</th>
                    <th>Редактирование</th>
                    <th>Удалить</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_merge($workers, $managers) as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td>
                        
                        <a href="edit_user.php?id=<?php echo htmlspecialchars($user['id']); ?>">Редактировать</a>
                        </td>
                        <td><a href="delete_worker.php?id=<?php echo htmlspecialchars($user['id']); ?>" onclick="return confirm('Are you sure you want to delete this worker?');">Удалить</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </section>
    </main>
</body>
</html>