<?php
session_start();
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'] ?? '';
    $user_id = $_POST['user_id'] ?? '';
    $description = trim($_POST['description'] ?? '');
    $sector = trim($_POST['sector'] ?? '');
    $priority = $_POST['priority'] ?? '';
    $status = $_POST['status'] ?? '';
    
   
    if (empty($task_id) || empty($user_id) || empty($description) || empty($sector) || empty($priority) || empty($status)) {
        $_SESSION['message'] = 'Todos os campos são obrigatórios.';
        $_SESSION['message_type'] = 'message-error';
        header('Location: editar_tarefa.php?id=' . $task_id);
        exit();
    }

    
    $valid_priorities = ['baixa', 'média', 'alta'];
    $valid_statuses = ['a fazer', 'fazendo', 'pronto'];
    
    if (!in_array($priority, $valid_priorities) || !in_array($status, $valid_statuses)) {
        $_SESSION['message'] = 'Valores inválidos para prioridade ou status.';
        $_SESSION['message_type'] = 'message-error';
        header('Location: editar_tarefa.php?id=' . $task_id);
        exit();
    }

    try {
        
        $stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        
        if ($stmt->rowCount() === 0) {
            $_SESSION['message'] = 'Usuário não encontrado.';
            $_SESSION['message_type'] = 'message-error';
            header('Location: editar_tarefa.php?id=' . $task_id);
            exit();
        }

     
        $stmt = $conn->prepare("
            UPDATE tasks 
            SET user_id = ?, description = ?, sector = ?, priority = ?, status = ? 
            WHERE id = ?
        ");
        $stmt->execute([$user_id, $description, $sector, $priority, $status, $task_id]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['message'] = 'Tarefa atualizada com sucesso!';
            $_SESSION['message_type'] = 'message-success';
            header('Location: gerenciar_tarefas.php');
        } else {
            $_SESSION['message'] = 'Nenhuma alteração foi feita ou a tarefa não foi encontrada.';
            $_SESSION['message_type'] = 'message-error';
            header('Location: editar_tarefa.php?id=' . $task_id);
        }
    } catch(PDOException $e) {
        $_SESSION['message'] = 'Erro ao atualizar tarefa: ' . $e->getMessage();
        $_SESSION['message_type'] = 'message-error';
        header('Location: editar_tarefa.php?id=' . $task_id);
    }
} else {
    header('Location: gerenciar_tarefas.php');
}
exit();