<?php
session_start();
require("../../../adodb5/adodb.inc.php");
require("../../../conecta.php");
require('cidades.class.php');

$cidade = new Cidades();

$acao = "";
$id = 0;
if (isset($_REQUEST['acao']))
$acao = $_REQUEST['acao'];

if (isset($_REQUEST['id']))
$id = $_REQUEST['id'];

switch ($acao) {
	case '':
	echo $cidade->getDados();
	break;
	case 'add':
	echo $cidade->addCidade($_REQUEST['nome'], $_REQUEST['uf']);
	break;
	case 'edit':
	echo $cidade->editCidade($id, $_REQUEST['nome'], $_REQUEST['uf']);
	break;
	case 'del':
	if($cidade->violacao_integridade($id) > 0){
		echo "Erro ao exluir cidade";
	}else{
		echo $cidade->delCidade($id);
	}
	break;
	case 'getCidade':
	echo $cidade->getCidade($id);
	break;
}
?>
