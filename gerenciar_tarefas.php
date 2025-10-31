<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Gerencie e visualize todas as tarefas no Sistema de Gerenciamento de Tarefas">
    <title>Gerenciamento de Tarefas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="nav">
        <div class="nav-container">
            <a href="index.php">Início</a>
            <a href="cadastro_usuario.php">Cadastrar Usuário</a>
            <a href="cadastro_tarefa.php">Cadastrar Tarefa</a>
            <a href="gerenciar_tarefas.php">Gerenciar Tarefas</a>
        </div>
    </nav>
    <div class="container">
        <h1>Gerenciamento de Tarefas</h1>
        <?php
        require_once 'includes/config.php';
        
        if (isset($_SESSION['message'])) {
            echo '<div class="message ' . $_SESSION['message_type'] . '">' . $_SESSION['message'] . '</div>';
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }

        try {
            $stmt = $conn->query("
                SELECT t.*, u.name as user_name 
                FROM tasks t 
                JOIN users u ON t.user_id = u.id 
                ORDER BY t.priority DESC, t.created_at DESC
            ");
            $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            
            $tasksByStatus = [
                'a fazer' => [],
                'fazendo' => [],
                'pronto' => []
            ];
            
            foreach ($tasks as $task) {
                $tasksByStatus[$task['status']][] = $task;
            }
        } catch(PDOException $e) {
            echo '<div class="message message-error">Erro ao carregar tarefas.</div>';
            $tasksByStatus = [
                'a fazer' => [],
                'fazendo' => [],
                'pronto' => []
            ];
        }
        ?>
        
        <div class="board">
            <?php
            $statusTitles = [
                'a fazer' => 'A Fazer',
                'fazendo' => 'Fazendo',
                'pronto' => 'Pronto'
            ];
            
            foreach ($tasksByStatus as $status => $tasks): ?>
                <div class="column">
                    <h2><?php echo $statusTitles[$status]; ?></h2>
                    <?php foreach ($tasks as $task): ?>
                        <div class="task-card priority-<?php echo $task['priority']; ?>">
                            <h3><?php echo htmlspecialchars($task['description']); ?></h3>
                            <p><strong>Setor:</strong> <?php echo htmlspecialchars($task['sector']); ?></p>
                            <p><strong>Usuário:</strong> <?php echo htmlspecialchars($task['user_name']); ?></p>
                            <p><strong>Prioridade:</strong> <?php echo ucfirst($task['priority']); ?></p>
                            <div class="task-actions">
                                <form action="atualizar_tarefa.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                    <select name="new_status" onchange="this.form.submit()">
                                        <option value="a fazer" <?php echo $task['status'] === 'a fazer' ? 'selected' : ''; ?>>A Fazer</option>
                                        <option value="fazendo" <?php echo $task['status'] === 'fazendo' ? 'selected' : ''; ?>>Fazendo</option>
                                        <option value="pronto" <?php echo $task['status'] === 'pronto' ? 'selected' : ''; ?>>Pronto</option>
                                    </select>
                                </form>
                                <a href="editar_tarefa.php?id=<?php echo $task['id']; ?>" class="btn btn-primary">Editar</a>
                                <form action="excluir_tarefa.php" method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja excluir esta tarefa?');">
                                    <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                    <button type="submit" class="btn btn-danger">Excluir</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>