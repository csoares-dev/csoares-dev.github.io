<?php

$host = "localhost";    // Ou "127.0.0.1"
$user = "root";         // Usuário padrão do XAMPP
$pass = "senac@2025";             // <<< A SENHA DEVE SER VAZIA!
$db   = "soares_log";     // O nome do nosso banco de dados
$port = 3307;           // A nova porta que configuramos!

// A conexão agora inclui a variável da porta
$connect = new mysqli($host, $user, $pass, $db, $port);

// Verifica se a conexão falhou
if ($connect->connect_error) {
    die("Falha na conexão: " . $connect->connect_error);
}
?>