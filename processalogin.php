<?php
session_start();

// 1. INCLUIR NOSSO ARQUIVO DE CONEXÃO
require_once 'config/conexao.php';

// 2. VERIFICAR SE O FORMULÁRIO FOI ENVIADO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha_digitada = $_POST['senha'];

    // 3. BUSCAR O USUÁRIO NO BANCO DE DADOS PELO E-MAIL
    $stmt = $connect->prepare("SELECT id, nome, senha FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // 4. VERIFICAR SE O USUÁRIO EXISTE
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $nome, $senhaHash);
        $stmt->fetch();

        // IF PARA VERIFICAR SE A SENHA ESTÁ CORRETA
        if (password_verify($senha_digitada, $senhaHash)) {
            
            // 6. SALVAR DADOS IMPORTANTES NA SESSÃO
            $_SESSION['usuario_id'] = $id;
            $_SESSION['usuario_nome'] = $nome; // Vamos guardar o nome para dar boas-vindas

            //Onde o SUBMIT irá levar
            header("Location: home.php");
            exit;
        } else {
            // Senha incorreta
            $_SESSION['login_erro'] = "Senha incorreta. Por favor, tente novamente.";
            header("Location: index.php");
            exit;
        }
    } else {
        // Usuário não encontrado
        $_SESSION['login_erro'] = "Usuário não encontrado.";
        header("Location: index.php");
        exit;
    }
    $stmt->close();
} else {
    header("Location: index.php");
    exit;
}
?>