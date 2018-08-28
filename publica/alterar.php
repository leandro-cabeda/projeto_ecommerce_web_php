<?php
	session_start();
	require("../adodb5/adodb.inc.php");
	require("../conecta.php");
	$con = new conecta();
	$sessao = $_SESSION['id'];

	$id = filter_input(INPUT_GET, 'ref');
	$qtd = filter_input(INPUT_GET, 'qtd');

	$sql = "update cesta_itens set cite_qtd = $qtd where pro_id = $id and ces_sessao = '$sessao'";
	$res = $con->bd->Execute($sql) or die("Erro na atualização: ".$con->bd->ErrorMsg());
	if ($res) {
		$sql1 = "select * from cesta_itens where pro_id = $id and ces_sessao = '$sessao' and cite_qtd = $qtd";
		$res1 = $con->bd->Execute($sql1) or die("Erro na atualização: ".$con->bd->ErrorMsg());
		if ($res1) {
			$valor_total = "R$ ".number_format($res1->fields['cite_valor'] * $res1->fields['cite_qtd'],2,",",".");
			$output = array("valor_total" => $valor_total);
			echo json_encode($output);
		}else{
			echo "error";
		}
	}else {
		echo "error";
	}

?>
