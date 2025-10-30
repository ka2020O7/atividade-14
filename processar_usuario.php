<?php
session_start();
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    
    if (empty($name) || empty($email)) {
        $_SESSION['message'] = 'Todos os campos são obrigatórios.';
        $_SESSION['message_type'] = 'message-error';
        header('Location: cadastro_usuario.php');
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = 'Por favor, insira um e-mail válido.';
        $_SESSION['message_type'] = 'message-error';
        header('Location: cadastro_usuario.php');
        exit();
    }

    try {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['message'] = 'Este e-mail já está cadastrado.';
            $_SESSION['message_type'] = 'message-error';
            header('Location: cadastro_usuario.php');
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
        $stmt->execute([$name, $email]);

        $_SESSION['message'] = 'Cadastro concluído com sucesso!';
        $_SESSION['message_type'] = 'message-success';
        header('Location: cadastro_usuario.php');
        exit();

    } catch(PDOException $e) {
        $_SESSION['message'] = 'Erro ao cadastrar usuário: ' . $e->getMessage();
        $_SESSION['message_type'] = 'message-error';
        header('Location: cadastro_usuario.php');
        exit();
    }
} else {
    header('Location: cadastro_usuario.php');
    exit();
}