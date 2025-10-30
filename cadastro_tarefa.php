<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Tarefa</title>
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
        <h1>Cadastro de Tarefa</h1>
        <?php
        require_once 'includes/config.php';
        
        if (isset($_SESSION['message'])) {
            echo '<div class="message ' . $_SESSION['message_type'] . '">' . $_SESSION['message'] . '</div>';
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }

        
        try {
            $stmt = $conn->query("SELECT id, name FROM users ORDER BY name");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo '<div class="message message-error">Erro ao carregar usuários.</div>';
            $users = [];
        }
        ?>
        <form action="processar_tarefa.php" method="POST">
            <div class="form-group">
                <label for="user_id">Usuário:</label>
                <select id="user_id" name="user_id" required>
                    <option value="">Selecione um usuário</option>
                    <?php foreach($users as $user): ?>
                        <option value="<?php echo $user['id']; ?>">
                            <?php echo htmlspecialchars($user['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Descrição da Tarefa:</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="sector">Setor:</label>
                <input type="text" id="sector" name="sector" required>
            </div>
            <div class="form-group">
                <label for="priority">Prioridade:</label>
                <select id="priority" name="priority" required>
                    <option value="baixa">Baixa</option>
                    <option value="média">Média</option>
                    <option value="alta">Alta</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar Tarefa</button>
        </form>
    </div>
</body>
</html>