<?php
session_start();
require_once 'db_connection.php';

// ���������, ���� �� � ������ ������ � ������������ � ����� �� �� ���� "director"
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'director') {
    header("Location: login.html");
    exit();
}

// �������� ID ������������ �� GET �������
$userIdToEdit = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Хеширование пароля

    $sql = "UPDATE users SET username = ?, password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $username, $password, $userIdToEdit);
    if ($stmt->execute()) {
        // Перенаправление на страницу со списком пользователей после успешного обновления
        $_SESSION['message'] = 'User updated successfully.';
        header('Location: director_workers.php'); // Адаптируйте адрес в соответствии с вашими нуждами
        exit();
    } else {
        echo "An error occurred or no changes were made.";
    }

} else {
    // ���� �������� �����������, � �� ������������ �����, �������� ������� ������ ������������
    $sql = "SELECT id, username FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userIdToEdit);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>
<body>
    <h1>Edit User</h1>
    <?php if (isset($user)): ?>
        <form action="edit_user.php?id=<?php echo htmlspecialchars($userIdToEdit); ?>" method="post" onsubmit="return confirmUpdate();">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br>

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required><br>

    <input type="submit" value="Update User">
</form>

<script>
    function confirmUpdate() {
        return confirm("Хотите ли вы обновить пользователя?");
    }
</script>
    <?php else: ?>
        <p>User not found.</p>
    <?php endif; ?>
    <script>
        function confirmUpdate() {
            var updateConfirmed = confirm("Хотите ли вы обновить пользователя?");
            if (updateConfirmed) {
                // Пользователь нажал "ОК", форма будет отправлена
                return true;
            } else {
                // Пользователь нажал "Отмена", отправка формы отменяется
                return false;
            }
        }
    </script>
</body>
</html>