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
        <div class="header"><h1>Система учета рабочего времени</h1></div>
    </header>
    <main>
        <div class="top-bar"> 
        <div class="toggle-button"><span>&#9776;</span></div>   
            <div class="left-menu">
                
                <h2>Добро пожаловать, <?php echo $username; ?>!</h2>
                <div class="navigation">
                    <nav>
                        <ul>
                            <?php if ($_SESSION['role'] === 'director'): ?>
                                <li><a href="director_dashboard.php">Рабочая панель</a></li>
                                <li><a href="director_tasks.php">Задания</a></li>
                                <li><a href="director_workers.php">Сотрудники</a></li>
                            <?php endif; ?>
                            <?php if ($_SESSION['role'] === 'manager'): ?>
                                <li><a href="manager_dashboard.php">Рабочая панель</a></li>
                                <li><a href="manager_tasks.php">Задания</a></li>
                                <li><a href="manager_workers.php">Сотрудники</a></li>
                            <?php endif; ?>
                            <?php if ($_SESSION['role'] === 'worker'): ?>
                                <li><a href="worker_dashboard.php">Рабочая панель</a></li>
                                <li><a href="worker_tasks.php">Задания</a></li>
                            <?php endif; ?>
                            <li><a href="logout.php">Выход</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </main>
    <script src="menu.js"></script>
</body>
</html>
