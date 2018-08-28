<?php
session_start();

include '../adodb5/adodb.inc.php';
include '../conecta.php';
include 'pedidos.class.php';

$pedidos = new Pedidos();
// error_reporting(E_ALL ^ E_NOTICE);

$acao = "";
$id = 0;
if(isset($_REQUEST['acao']))
	$acao = $_REQUEST['acao'];

if(isset($_REQUEST['id']))
	$id = $_REQUEST['id'];

if(isset($_SESSION['usu_codigo']))
	$id_cli = $_SESSION['usu_codigo'];

switch ($acao) {
	case '':
		echo $pedidos->getDados($id_cli);
	break;
	case 'getItensPedido':
		echo $pedidos->getItensPedido($id);
	break;
}

?>
