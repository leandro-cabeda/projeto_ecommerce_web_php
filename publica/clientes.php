<?php
include '../adodb5/adodb.inc.php';
include '../conecta.php';
include 'clientes.class.php';
$cliente = new Clientes();
error_reporting(E_ALL ^ E_NOTICE);

function Mask_List($mask,$str){
	$str = str_replace(" ","",$str);
	for($i=0;$i<strlen($str);$i++){
		$mask[strpos($mask,"#")] = $str[$i];
	}
	return $mask;
}

$acao = "";
$id = 0;
if(isset($_REQUEST['acao']))
$acao = $_REQUEST['acao'];

if(isset($_REQUEST['id']))
$id = $_REQUEST['id'];


$email = $_POST['email'];
$senha = $_POST['senha'];
$cpf = $_POST['cpf'];
$cep = $_POST['cep'];
$fone1 = $_POST['fone1'];
$fone2 = $_POST['fone2'];
$sexo = $_POST['sexo'];
$nome = $_POST['nome'];
$endereco = $_POST['endereco'];
$cidade = $_POST['cidade'];
$bairro = $_POST['bairro'];
$dtNasc = $_POST['dtNasc'];
$mala = $_POST['mala'];

switch ($acao){
	case 'add':
	echo $cliente->addCliente($email, $senha, $cpf, $cep, $fone1, $fone2, $sexo, $nome, $endereco, $cidade, $bairro, $dtNasc, $mala);
	break;
	case 'edit':
	echo $cliente->editCliente($id, $email, $senha, $cpf, $cep, $fone1, $fone2, $sexo, $nome, $endereco, $cidade, $bairro, $dtNasc, $mala);
	break;
	case 'getCliente':
	echo $cliente->getCliente($id);
	break;
	case 'getmenucidade':
	echo $cliente->getmenucidades('');
	break;
}

?>
