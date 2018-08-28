<?php
session_start();
require("../adodb5/adodb.inc.php");
require("../conecta.php");

$sessao = $_SESSION['id'];

$con = new conecta();
// error_reporting(E_ALL ^ E_NOTICE);

if (isset($_POST['tipo_frete'])) {
	$tipo_frete = filter_input(INPUT_POST, 'tipo_frete');
	$tipo_pag = filter_input(INPUT_POST, 'tipopag');
	$ped_status = filter_input(INPUT_POST, 'ped_status');
	$cli_id = filter_input(INPUT_POST, 'cli_id');
	$cli_endereco = filter_input(INPUT_POST, 'cli_endereco');
	$cli_bairro = filter_input(INPUT_POST, 'cli_bairro');
	$cli_cep = filter_input(INPUT_POST, 'cli_cep');
	$cid_id = filter_input(INPUT_POST, 'cid_id');
	$total = filter_input(INPUT_POST, 'total');
	$frete = filter_input(INPUT_POST, 'frete');

	$sql = "INSERT INTO pedidos (ped_data, ped_hora, ped_total, ped_frete, ped_tipopag, ped_status, cli_id, ped_tipofrete, ped_endereco, ped_bairro, ped_cep, cid_id)
	VALUES (current_date, date_trunc('second', clock_timestamp())::time, '$total', '$frete', '$tipo_pag', '$ped_status', $cli_id, '$tipo_frete', '$cli_endereco', '$cli_bairro', '$cli_cep', $cid_id)";
	$res = $con->bd->Execute($sql) or die("Erro na inserção na tabela de pedidos: ".$con->bd->ErrorMsg());
	if (!$res){
		echo "error";
	} else {
		$ped_max_id = "SELECT * FROM pedidos WHERE ped_id = (SELECT MAX(ped_id) FROM pedidos)";
		$res_max_id = $con->bd->Execute($ped_max_id) or die("Erro na inserção: ".$con->bd->ErrorMsg());
		$sql_cesta = "SELECT * FROM cesta_itens ci, produtos p WHERE ci.pro_id = p.pro_id and ces_sessao = '$sessao'";
		$res_cesta = $con->bd->Execute($sql_cesta) or die("Erro na consulta: ".$con->bd->ErrorMsg());
		while(!$res_cesta->EOF){
			// insere os itens do carrrinho na tabela pedidos_itens
			$inserir = "INSERT INTO pedidos_itens (ped_id, pro_id, ite_qtd, ite_valor) VALUES (".$res_max_id->fields['ped_id'].", ".$res_cesta->fields['pro_id'].", ".$res_cesta->fields['cite_qtd'].", ".$res_cesta->fields['cite_valor'].")";
			$res_inserir = $con->bd->Execute($inserir) or die("Erro na insersão em pedidos_itens: ".$con->bd->ErrorMsg());
			// atualiza o estoque do produto após a venda
			$res_cesta->fields['pro_estoque'] = $res_cesta->fields['pro_estoque'] - $res_cesta->fields['cite_qtd'];
			$alterar = "update produtos set pro_estoque = ".$res_cesta->fields['pro_estoque']." where pro_id = ".$res_cesta->fields['pro_id'];
			$res_alterar = $con->bd->Execute($alterar) or die("Erro na atualização de estoque: ".$con->bd->ErrorMsg());

			$res_cesta->MoveNext();
		}
		unset($_SESSION['id']);
		echo "ok";
	}

}

?>
