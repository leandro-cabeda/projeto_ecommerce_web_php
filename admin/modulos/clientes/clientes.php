<?php
session_start();
require("../../../adodb5/adodb.inc.php");
require("../../../conecta.php");
require('clientes.class.php');

function Mask_List($mask,$str){
	$str = str_replace(" ","",$str);
	for($i=0;$i<strlen($str);$i++){
		$mask[strpos($mask,"#")] = $str[$i];
	}
	return $mask;
}

$cliente = new Clientes();

$acao = "";
$maladireta = "";
$status = "";
$id = 0;

if (isset($_REQUEST['acao']))
$acao = $_REQUEST['acao'];

if (isset($_REQUEST['id']))
$id = $_REQUEST['id'];

switch ($acao) {
	case '':
	echo $cliente->getDados();
	break;
	case 'add':
	if (!isset($_REQUEST['status'])) {
		$status = "I";
	}else{
		$status = "A";
	}
	if (!isset($_REQUEST['maladireta'])) {
		$maladireta = "N";
	}else{
		$maladireta = "S";
	}
	echo $cliente->addCliente($_REQUEST['nome'], $_REQUEST['cpf'], $_REQUEST['endereco'], $_REQUEST['email'], $_REQUEST['sexo'], $_REQUEST['bairro'], $_REQUEST['cep'], $_REQUEST['fone1'], $_REQUEST['fone2'], $_REQUEST['datanasc'], $status, $maladireta, $_REQUEST['cidade'], $_REQUEST['tipo'], $_REQUEST['senha']);
	break;
	case 'edit':
	if (!isset($_REQUEST['status'])) {
		$status = "I";
	}else{
		$status = "A";
	}
	if (!isset($_REQUEST['maladireta'])) {
		$maladireta = "N";
	}else{
		$maladireta = "S";
	}
	echo $cliente->editCliente($id, $_REQUEST['nome'], $_REQUEST['cpf'], $_REQUEST['endereco'], $_REQUEST['email'], $_REQUEST['sexo'], $_REQUEST['bairro'], $_REQUEST['cep'], $_REQUEST['fone1'], $_REQUEST['fone2'], $_REQUEST['datanasc'], $status, $maladireta, $_REQUEST['cidade'], $_REQUEST['tipo'], $_REQUEST['senha']);
	break;
	case 'del':
	if($cliente->violacao_integridade($id) > 0){
		echo "Erro ao excluir cliente!";
	}else{
		echo $cliente->delCliente($id);
	}
	break;
	case 'getCliente':
	echo $cliente->getCliente($id);
	break;
	case "get_MenuCidades":
	echo $cliente->getMenuCidades("");
	break;
}
?>
