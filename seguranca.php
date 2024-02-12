<?php
session_start();
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('America/Sao_Paulo');
if(($_SESSION['email_usuario']== "") || ($_SESSION['senha_usuario']== "")){
    header("Location:index.php");
}

?>