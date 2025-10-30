<?php
session_start();
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'] ?? '';
    
    if (empty($task_id)) {
        $_SESSION['message'] = 'ID da tarefa não fornecido.';
        $_SESSION['message_type'] = 'message-error';
        header('Location: gerenciar_tarefas.php');
        exit();
    }

    try {
        $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->execute([$task_id]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['message'] = 'Tarefa excluída com sucesso!';
            $_SESSION['message_type'] = 'message-success';
        } else {
            $_SESSION['message'] = 'Tarefa não encontrada.';
            $_SESSION['message_type'] = 'message-error';
        }
    } catch(PDOException $e) {
        $_SESSION['message'] = 'Erro ao excluir tarefa: ' . $e->getMessage();
        $_SESSION['message_type'] = 'message-error';
    }
}

header('Location: gerenciar_tarefas.php');
exit();