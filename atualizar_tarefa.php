<?php
session_start();
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'] ?? '';
    $new_status = $_POST['new_status'] ?? '';
    
    
    if (empty($task_id) || empty($new_status)) {
        $_SESSION['message'] = 'Parâmetros inválidos.';
        $_SESSION['message_type'] = 'message-error';
        header('Location: gerenciar_tarefas.php');
        exit();
    }

    
    $valid_statuses = ['a fazer', 'fazendo', 'pronto'];
    if (!in_array($new_status, $valid_statuses)) {
        $_SESSION['message'] = 'Status inválido.';
        $_SESSION['message_type'] = 'message-error';
        header('Location: gerenciar_tarefas.php');
        exit();
    }

    try {
        $stmt = $conn->prepare("UPDATE tasks SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $task_id]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['message'] = 'Status da tarefa atualizado com sucesso!';
            $_SESSION['message_type'] = 'message-success';
        } else {
            $_SESSION['message'] = 'Tarefa não encontrada.';
            $_SESSION['message_type'] = 'message-error';
        }
    } catch(PDOException $e) {
        $_SESSION['message'] = 'Erro ao atualizar status: ' . $e->getMessage();
        $_SESSION['message_type'] = 'message-error';
    }
}

header('Location: gerenciar_tarefas.php');
exit();