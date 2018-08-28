<?php
session_start();
unset($_SESSION['user_session']); //retirar variável da sessão

if (session_destroy()) { // destruir a sessão
    header("Location: index.php");
}
?>