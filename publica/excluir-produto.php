<?php
	session_start();
	require("../adodb5/adodb.inc.php");
	require("../conecta.php");
	$con = new conecta();

	$id = filter_input(INPUT_GET, 'ref');
	$deletar = $con->bd->Execute("delete from cesta_itens where pro_id = $id");
	if ($deletar) {
		echo "ok";
	}else {
		echo "erro";
	}
?>
