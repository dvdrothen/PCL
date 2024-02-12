<?php
include_once 'config.php';
$usuario = $_POST['usuario'];
$senha = $_POST['senha'];

//login
if(USUARIO == $usuario && SENHA == $senha) {
    session_start();
    $_SESSION['email_usuario'] = $usuario;
    $_SESSION['senha_usuario'] = $senha;
    header("Location: chat.php");
} else {
    echo '<script>alert("Usuário ou senha incorretos!");</script>';
    echo '<script>window.location.href="index.php";</script>';
}

?>