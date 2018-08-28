<?php
	session_start();
	require("../adodb5/adodb.inc.php");
	require("../conecta.php");
	$con = new conecta();
	$sessao = $_SESSION['id'];
	$pro_id = filter_input(INPUT_GET, 'prod');// pega o id do produto que está sendo passado por parâmetro na URl

	// lista produto para pegar os dados
	$sql = "select * from produtos where pro_id = $pro_id";
	$res = $con->bd->Execute($sql) or die("Erro na consulta: ".$con->bd->ErrorMsg());
	while(!$res->EOF){
		$prod_id = $res->fields['pro_id'];
		$prod_nome = $res->fields['pro_descricao'];
		$prod_qtd = 1;
		if ($res->fields['pro_promocao']) {
			$pro_preco = $res->fields['pro_promocao'];
		}else{
			$pro_preco = $res->fields['pro_valor'];
		}
		$res->MoveNext();
	}

	// lista produtos da cesta da sessao atual
	$sql_cesta = "select * from cesta_itens where pro_id = $prod_id and ces_sessao = '$sessao'";
	$res = $con->bd->Execute($sql_cesta) or die("Erro na consulta: ".$con->bd->ErrorMsg());
	while(!$res->EOF){
		$qtd = $res->fields['cite_qtd'];
		$res->MoveNext();
	}
	$linhas = $res->RecordCount();
   // se tiver produtos na cesta, faz a atualização da quantidade
	if ($linhas >= 1) {
		$qtd = $qtd+1;
		$alterar = "update cesta_itens set cite_qtd = $qtd where pro_id = $prod_id and ces_sessao = '$sessao'";
		$res_alterar = $con->bd->Execute($alterar) or die("Erro na atualização: ".$con->bd->ErrorMsg());
		if ($res_alterar) {
			echo "<script>window.location='carrinho.php'</script>";
		}else {
			echo "<script>alert('Este produto não pode ser colocado no carrinho!')</script>";
			echo "<script>window.location='index.php'</script>";
		}
		//senão faz a inserção na cesta
	}else {
		$inserir = "insert into cesta_itens (ces_sessao, pro_id, cite_qtd, cite_valor) values	('$sessao', $prod_id, $prod_qtd, $pro_preco)";
		$res_inserir = $con->bd->Execute($inserir) or die("Erro na insersão: ".$con->bd->ErrorMsg());
		if ($res_inserir) {
			echo "<script>window.location='carrinho.php'</script>";
		}else {
			echo "<script>alert('Este produto não pode ser colocado no carrinho!')</script>";
			echo "<script>window.location='index.php'</script>";
		}
	}
?>
