<?php
session_start();
require("../adodb5/adodb.inc.php");
require("../conecta.php");

$con = new conecta();

error_reporting(E_ALL ^ E_NOTICE);

$tipo = $_GET['tipo'];
$cat_id = $_GET['id'];

$sql = "SELECT * FROM categorias c, produtos p  WHERE c.cat_id = p.cat_id AND p.cat_id = $cat_id";
$res = $con->bd->Execute($sql) or die("Erro na consulta de categoria: ".$con->bd->ErrorMsg());


if($tipo == 'listagem'){
	$pag = $_GET['pag'];
	$maximo = $_GET['maximo'];
	$inicio = ($pag * $maximo) - $maximo; //Variável para LIMIT da sql
	$sql_cat = "SELECT * FROM categorias c, produtos p  WHERE c.cat_id = p.cat_id AND p.cat_id = $cat_id ORDER BY p.cat_id LIMIT $maximo OFFSET $inicio";
	$result_cat = $con->bd->Execute($sql_cat) or die("Erro na consulta de categoria: ".$con->bd->ErrorMsg());

	while(!$result_cat->EOF){
		?>
		<div class="row">
			<div class="col-lg-4" style="color:black; font-weight:600; display: inline-block; height: 450px;">
				<img class="img-rounded" src="../../e-commerce/produtos/prod<?php echo $result_cat->fields['pro_id'].".jpg"; ?>" alt="Imagem do <?php echo $result_cat->fields['pro_descricao']; ?>" width="140" height="140">
				<h3><?php echo $result_cat->fields['pro_descricao']; ?></h3>
				<?php if ($result_cat->fields['pro_promocao']){ ?>
					<p>De: <s> <?php echo "R$ ".number_format($result_cat->fields['pro_valor'],2,",","."); ?></s></p>
					<p>Por: <?php echo "R$ ".number_format($result_cat->fields['pro_promocao'],2,",","."); ?></p>
				<?php } else { ?>
					<p>Preço: <?php echo "R$ ".number_format($result_cat->fields['pro_valor'],2,",","."); ?></p>
				<?php } ?>
				<p><a class="btn btn-success" href="comprar.php?prod=<?php echo $result_cat->fields['pro_id']; ?>" role="button"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Comprar</a></p>
				<p><a class="btn btn-primary" href="produtos.php?id=<?php echo $result_cat->fields['pro_id']; ?>" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Ver Mais</a></p>
			</div>

			<?php
			$result_cat->MoveNext();
		}
	}else if($tipo == 'contador'){
		$linhas = $res->RecordCount();
		echo $linhas;
	}else{
		echo "Solicitação inválida";
	}
	?>
