<?php
session_start();
require("../../../adodb5/adodb.inc.php");
require("../../../conecta.php");
require('pedidos.class.php');

$pedido = new Pedidos();

$acao = "";
$id = 0;
if (isset($_REQUEST['acao']))
$acao = $_REQUEST['acao'];

if (isset($_REQUEST['id']))
$id = $_REQUEST['id'];

switch ($acao) {
	case '':
	echo $pedido->getDados();
	break;
	case 'edit':
	echo $pedido->editPedido($id, $_REQUEST['status'], $_REQUEST['dataenvio']);
	break;
	case 'del':
	if($pedido->violacao_integridade($id) > 0){
		echo "Erro ao exluir pedido";
	}else{
		echo $pedido->delPedido($id);
	}
	break;
	case 'getPedido':
	echo $pedido->getPedido($id);
	break;
}
?>
