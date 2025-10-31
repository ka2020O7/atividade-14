<?php
session_start();
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? '';
    $description = trim($_POST['description'] ?? '');
    $sector = trim($_POST['sector'] ?? '');
    $priority = $_POST['priority'] ?? '';
    
    if (empty($user_id) || empty($description) || empty($sector) || empty($priority)) {
        $_SESSION['message'] = 'Todos os campos são obrigatórios.';
        $_SESSION['message_type'] = 'message-error';
        header('Location: cadastro_tarefa.php');
        exit();
    }

    $valid_priorities = ['baixa', 'média', 'alta'];
    if (!in_array($priority, $valid_priorities)) {
        $_SESSION['message'] = 'Prioridade inválida.';
        $_SESSION['message_type'] = 'message-error';
        header('Location: cadastro_tarefa.php');
        exit();
    }

    try {
        $stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        
        if ($stmt->rowCount() === 0) {
            $_SESSION['message'] = 'Usuário não encontrado.';
            $_SESSION['message_type'] = 'message-error';
            header('Location: cadastro_tarefa.php');
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO tasks (user_id, description, sector, priority) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $description, $sector, $priority]);

        $_SESSION['message'] = 'Tarefa cadastrada com sucesso!';
        $_SESSION['message_type'] = 'message-success';
        header('Location: cadastro_tarefa.php');
        exit();

    } catch(PDOException $e) {
        $_SESSION['message'] = 'Erro ao cadastrar tarefa: ' . $e->getMessage();
        $_SESSION['message_type'] = 'message-error';
        header('Location: cadastro_tarefa.php');
        exit();
    }
} else {
    header('Location: cadastro_tarefa.php');
    exit();
}