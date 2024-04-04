<?php
session_start();
require_once 'db_connection.php';

// ѕровер€ем, есть ли в сессии данные о пользователе и имеет ли он роль "director"
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'director') {
    header("Location: login.html");
    exit();
}

// ѕолучаем ID пользовател€ из GET запроса
$userIdToEdit = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // ’ешируем пароль

    $sql = "UPDATE users SET username = ?, password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $username, $password, $userIdToEdit);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo "User updated successfully.";
    } else {
        echo "An error occurred or no changes were made.";
    }
} else {
    // ≈сли страница загружаетс€, а не отправл€етс€ форма, получаем текущие данные пользовател€
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
        <form action="edit_user.php?id=<?php echo htmlspecialchars($userIdToEdit); ?>" method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required><br>

            <input type="submit" value="Update User">
        </form>
    <?php else: ?>
        <p>User not found.</p>
    <?php endif; ?>
</body>
</html>