<?php
// Inicia a sessão para que possamos usar as variáveis de sessão após o login
session_start();

// Inclui nosso arquivo de conexão que está na pasta config
require_once "config/conexao.php";

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Prepara a consulta de forma segura para evitar SQL Injection
    $stmt = $connect->prepare("SELECT id, senha FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0){
        $stmt->bind_result($id, $senhaHash);
        $stmt->fetch();

        // Verifica se a senha digitada corresponde à senha criptografada no banco
        if(password_verify($senha, $senhaHash)){
            // Senha correta, inicia a sessão do usuário
            $_SESSION['usuario_id'] = $id;
            
            // CORREÇÃO 1: Redireciona para sua página de produtos
            header("Location: home.php"); // <-- MUDANÇA AQUI
            exit;  
        } else {
            $erro = "❌ Senha incorreta.";
        }
    } else {
        $erro = "❌ Usuário não encontrado. <a href='cadastro.php'>Cadastre-se</a>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <title>Soares Sports</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css"> 
  </head>
  <body>
     
    <div class="split-container">

        <div class="metade-imagem">
        </div>

        <div class="metade-formulario">

            <div class="cadastro-container">
                
                <?php if(isset($erro)) echo "<p class='error'>$erro</p>"; ?>

                <h1>Soares Sports</h1>
                
                <form action="" method="POST"> <label>Email:</label>
                    <input type="email" name="email" required><br>
                            
                    <label>Senha:</label>
                    <input type="password" name="senha" required> <br><br>
                    
                    <button type="submit" class="btn">Entrar</button>
                </form><br>
                
                <a href="cadastro.php">Cadastre-se</a>
            </div>

        </div>

    </div>

  </body>
</html>