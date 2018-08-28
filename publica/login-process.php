<?php
	session_start();
	require("../adodb5/adodb.inc.php");
	require("../conecta.php");

	$con = new conecta();
	// error_reporting(E_ALL ^ E_NOTICE);
	if (isset($_POST['email'])) {
		$email = $_POST['email'];
		$senha = $_POST['senha'];

		$sql = "select * from clientes where cli_email = '$email'";
		$res = $con->bd->Execute($sql) or die("Erro na consulta: ".$con->bd->ErrorMsg());
		if ($res->fields['cli_senha'] == $senha) {
			$_SESSION['usu_codigo'] = $res->fields['cli_id'];
			echo "ok";
		}else {
			echo "E-mail ou senha não existe!";
		}
	}

?>
