<?php
session_start();
require("../../../adodb5/adodb.inc.php");
require("../../../conecta.php");
require('categorias.class.php');

$categoria = new Categorias();
$acao = "";
$id = 0;
if (isset($_REQUEST['acao']))
$acao = $_REQUEST['acao'];

if (isset($_REQUEST['id']))
$id = $_REQUEST['id'];

switch ($acao) {
	case '':
	echo $categoria->getDados();
	break;
	case 'add':
	echo $categoria->addCategoria($_REQUEST['descricao']);
	break;
	case 'edit':
	echo $categoria->editCategoria($id, $_REQUEST['descricao']);
	break;
	case 'del':
	if($categoria->violacao_integridade($id) > 0){
		echo "Erro ao exluir categoria";
	}else{
		echo $categoria->delCategoria($id);
	}
	break;
	case 'getCategoria':
	echo $categoria->getCategoria($id);
	break;
}
?>
