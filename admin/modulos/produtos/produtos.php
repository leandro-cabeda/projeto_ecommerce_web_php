<?php
session_start();
require("../../../adodb5/adodb.inc.php");
require("../../../conecta.php");
require('produtos.class.php');

$produto = new Produtos();

$acao = "";
$id = 0;
if (isset($_REQUEST['acao']))
$acao = $_REQUEST['acao'];

if (isset($_REQUEST['id']))
$id = $_REQUEST['id'];

switch ($acao) {
	case '':
	echo $produto->getDados();
	break;
	case 'add':
	echo $produto->addProduto($_REQUEST['descricao'], $_REQUEST['valor'], $_REQUEST['detalhes'], $_REQUEST['estoque'], $_REQUEST['peso'], $_REQUEST['promocao'], $_REQUEST['categoria'], $_REQUEST['dimensao']);
	break;
	case 'edit':
	echo $produto->editProduto($id, $_REQUEST['descricao'], $_REQUEST['valor'], $_REQUEST['detalhes'], $_REQUEST['estoque'], $_REQUEST['peso'], $_REQUEST['promocao'], $_REQUEST['categoria'], $_REQUEST['dimensao']);
	break;
	case 'del':
	if($produto->violacao_integridade($id) > 0){
		echo "Erro ao excluir produto!";
	}else{
		echo $produto->delProduto($id);
	}
	break;
	case 'getProduto':
	echo $produto->getProduto($id);
	break;
	case "get_MenuCategorias":
	echo $produto->getMenuCategorias("");
	break;
}
?>
