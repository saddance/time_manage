<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Time Tracking App</title>
    <link rel="stylesheet" href="worker_style.css">
</head>
<body>
    <header>
    </header>

    <main>
        <div class="tasks_tables">
    <?php if ($_SESSION['role'] === 'director'): ?>
    
    
        <h2>Все задания:</h2>
        <?php if (!empty($tasks)): ?>
            <table>
                <thead>
                    <tr>
                    <th>Заголовок</th>
                    <th>Описание</th>
                    <th>Статус</th>
                    <th>Выполняющий</th>
                    <th>Проверяющий</th> <!-- �������� ������� ����������� -->
                    <th>Действие</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                        <td><a href="task_detail.php?id=<?php echo $task['id']; ?>"><?php echo htmlspecialchars($task['title']); ?></a></td>
                        <td><?php echo htmlspecialchars($task['description']); ?></td>
                        <td><?php echo htmlspecialchars($task['status']); ?></td>
                        <td><?php echo htmlspecialchars($task['assigned_to']); ?></td>
                        <td><?php echo htmlspecialchars($task['observer']); ?></td> <!-- ���������� ����������� ����������� -->
                        <td><a href="delete_task.php?id=<?php echo $task['id']; ?>" onclick="return confirm('Are you sure you want to delete this task?');">Удалить</a></td>
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

<?php endif; ?>
<?php if ($_SESSION['role'] === 'manager'): ?>
    
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
                        <td><a href="task_detail.php?id=<?php echo $task['id']; ?>"><?php echo htmlspecialchars($task['title']); ?></a></td>
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

    <?php endif; ?>
<?php if ($_SESSION['role'] === 'worker'): ?>
    
        <h2>Your Tasks</h2>
        <?php if (count($tasks) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                        <td><a href="task_detail.php?id=<?php echo $task['id']; ?>"><?php echo htmlspecialchars($task['title']); ?></a></td>
                            <td><?php echo $task['description']; ?></td>
                            <td><?php echo $task['status']; ?></td>
                            
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No tasks assigned to you.</p>
        <?php endif; ?>
    
    <?php endif; ?>
        </div>
</main>
</body>
</html>