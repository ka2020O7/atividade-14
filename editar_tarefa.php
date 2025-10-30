<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarefa</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="nav">
        <a href="index.php">Início</a>
        <a href="cadastro_usuario.php">Cadastrar Usuário</a>
        <a href="cadastro_tarefa.php">Cadastrar Tarefa</a>
        <a href="gerenciar_tarefas.php">Gerenciar Tarefas</a>
    </nav>
    <div class="container">
        <h1>Editar Tarefa</h1>
        <?php
        require_once 'includes/config.php';
        
        if (isset($_SESSION['message'])) {
            echo '<div class="message ' . $_SESSION['message_type'] . '">' . $_SESSION['message'] . '</div>';
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }

        $task_id = $_GET['id'] ?? 0;

        try {
            
            $stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
            $stmt->execute([$task_id]);
            $task = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$task) {
                echo '<div class="message message-error">Tarefa não encontrada.</div>';
                exit();
            }

            
            $stmt = $conn->query("SELECT id, name FROM users ORDER BY name");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo '<div class="message message-error">Erro ao carregar dados: ' . $e->getMessage() . '</div>';
            exit();
        }
        ?>
        
        <form action="processar_edicao_tarefa.php" method="POST">
            <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
            <div class="form-group">
                <label for="user_id">Usuário:</label>
                <select id="user_id" name="user_id" required>
                    <?php foreach($users as $user): ?>
                        <option value="<?php echo $user['id']; ?>" <?php echo $user['id'] == $task['user_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($user['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Descrição da Tarefa:</label>
                <textarea id="description" name="description" required><?php echo htmlspecialchars($task['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="sector">Setor:</label>
                <input type="text" id="sector" name="sector" value="<?php echo htmlspecialchars($task['sector']); ?>" required>
            </div>
            <div class="form-group">
                <label for="priority">Prioridade:</label>
                <select id="priority" name="priority" required>
                    <option value="baixa" <?php echo $task['priority'] === 'baixa' ? 'selected' : ''; ?>>Baixa</option>
                    <option value="média" <?php echo $task['priority'] === 'média' ? 'selected' : ''; ?>>Média</option>
                    <option value="alta" <?php echo $task['priority'] === 'alta' ? 'selected' : ''; ?>>Alta</option>
                </select>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="a fazer" <?php echo $task['status'] === 'a fazer' ? 'selected' : ''; ?>>A Fazer</option>
                    <option value="fazendo" <?php echo $task['status'] === 'fazendo' ? 'selected' : ''; ?>>Fazendo</option>
                    <option value="pronto" <?php echo $task['status'] === 'pronto' ? 'selected' : ''; ?>>Pronto</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            <a href="gerenciar_tarefas.php" class="btn">Cancelar</a>
        </form>
    </div>
</body>
</html>