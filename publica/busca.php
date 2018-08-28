<?php
	session_start();
	require("../adodb5/adodb.inc.php");
	require("../conecta.php");
	$con = new conecta();


	if (isset($_POST['palavra'])) {
		$palavra = $_POST['palavra'];
		$palavra = trim($palavra);
		$palavra = strtoupper($palavra);
		$res_prod = $con->bd->Execute("select * from produtos where upper(pro_descricao) like '%$palavra%' limit 4") or die("Erro na pesquisa de produto: ".$con->bd->ErrorMsg());
		$linhas = $res_prod->RecordCount();
		if ($linhas <= 0) {
			echo "<li class='list-group-item'>"."Nenhum produto encontrado..."."</li>";
		}else{
			while(!$res_prod->EOF){
				echo "<a href='produtos.php?id=".$res_prod->fields['pro_id']."' class='list-group-item' style='height: 2.62em;'><b>".$res_prod->fields['pro_descricao']."</b></a>";
				$res_prod->MoveNext();
			}
		}
	}else{
		echo "erro";
	}
?>
