CREATE DATABASE IF NOT EXISTS your_database_name;
USE your_database_name;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    status VARCHAR(50) NOT NULL DEFAULT 'pending',
    assigned_to INT,
    created_by INT,
    FOREIGN KEY (assigned_to) REFERENCES users(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);
ALTER TABLE tasks ADD COLUMN observer INT NULL;
ALTER TABLE tasks ADD CONSTRAINT fk_tasks_observer FOREIGN KEY (observer) REFERENCES users(id);
---ALTER TABLE users ADD COLUMN user_log INT NULL; ��������
---ALTER TABLE users ADD CONSTRAINT fk_user_log FOREIGN KEY (observer) REFERENCES users(id);��������
ALTER TABLE tasks

ADD COLUMN start_date DATE NULL,
ADD COLUMN due_date DATE NULL;

-- Устанавливаем внешние ключи для наблюдателя
ALTER TABLE tasks
ADD CONSTRAINT fk_tasks_observer FOREIGN KEY (observer) REFERENCES users(id);