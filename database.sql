
CREATE DATABASE IF NOT EXISTS task_management;
USE task_management;


CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE
);


CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    description TEXT NOT NULL,
    sector VARCHAR(100) NOT NULL,
    priority ENUM('baixa', 'média', 'alta') NOT NULL,
    status ENUM('a fazer', 'fazendo', 'pronto') NOT NULL DEFAULT 'a fazer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);