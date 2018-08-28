<?php
session_start();
require("../adodb5/adodb.inc.php");
require("../conecta.php");
include('/modulos/clientes/clientes.class.php');
$cliente = new Clientes();
if(isset($_POST['user'])){
	$user = $_POST['user'];
	$senha = $_POST['password'];
	echo $cliente->verifica($user, $senha);
}
?>
