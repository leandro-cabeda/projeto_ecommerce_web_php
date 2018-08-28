<?php
session_start();
require("../adodb5/adodb.inc.php");
require("../conecta.php");
$con = new conecta();
error_reporting(E_ALL ^ E_NOTICE);
$sessao = $_SESSION['id'];
if (!isset($_SESSION['id'])) {
	$_SESSION['id'] = uniqid(NULL, true);
	if (!isset($_SESSION['usu_codigo'])){
		$result_session = $con->bd->Execute("insert into cesta (ces_sessao, ces_data, ces_hora) values ('".$_SESSION['id']."', current_date, date_trunc('second', clock_timestamp())::time)") or die("Erro na inserção: ".$con->bd->ErrorMsg());
	}else {
		$sessao_usuario = $_SESSION['usu_codigo'];
		$result_session = $con->bd->Execute("insert into cesta (ces_sessao, ces_data, ces_hora, cli_id) values ('".$_SESSION['id']."', current_date, date_trunc('second', clock_timestamp())::time, $sessao_usuario)") or die("Erro na inserção: ".$con->bd->ErrorMsg());
	}
}else {
	if (isset($_SESSION['usu_codigo'])){
		$sessao_usuario = $_SESSION['usu_codigo'];
		$sql = "update cesta set cli_id = $sessao_usuario where ces_sessao = '$sessao'";
		$result_session = $con->bd->Execute($sql) or die("Erro na inserção: ".$con->bd->ErrorMsg());
	}
}

$sql = "select * from produtos p, cesta_itens ci where p.pro_id = ci.pro_id and ces_sessao = '$sessao'";
$res = $con->bd->Execute($sql) or die("Erro na consulta: ".$con->bd->ErrorMsg());
$linhas = $res->RecordCount();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Loja GLM</title>
	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<link href="../css/carousel.css" rel="stylesheet">
	<link href="../css/style.css" rel="stylesheet">
</head>
<body>
	<nav class="navbar navbar-inverse " role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
				<a class="navbar-brand" href="index.php" style="font-size: 14pt; font-weight:700;">GLM Informática</a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right" style="font-size: 13pt; font-weight:700;">
					<?php
					if (!$_SESSION['usu_codigo']) {
						?>

						<li><a href="login.php">Entrar</a></li>
						<li><a href="login.php">Meus Pedidos</a></li>
						<?php
					}else{
						$cli_id = $_SESSION['usu_codigo'];
						$sql = "select * from clientes where cli_id = $cli_id";
						$res = $con->bd->Execute($sql) or die("Erro na consulta: ".$con->bd->ErrorMsg());
						?>
						<li class="dropdown">
							<a href="#" data-toggle="dropdown" class="dropdown-toggle">
								Olá, <?php echo $res->fields['cli_nome']; ?> <b class="caret"></b>
							</a>
							<ul class="dropdown-menu" style="width:100%; text-align: center;">
								<li><a href="cliente-pedidos.php">Meus Pedidos</a></li>
								<li><a href="cadastro.php">Alterar Dados</a></li>
								<li class="divider"></li>
								<li><a href="logout.php">Sair</a></li>
							</ul>
						</li>
						<?php
					}
					?>
					<li><a href="sobre.php">Sobre nós</a></li>
					<li><a href="contato.php">Fale Conosco</a></li>
				</ul>
			</div>
		</div>
	</nav>

	<div class="container">
		<div class="row">
			<div class="col-xs-4 col-sm-4 col-md-3">
				<div id="im"><a href="index.php"><img class="img-responsive" src="img/logo.png" width="120" height="90"/></a></div>
			</div>
			<div class="col-xs-4 col-sm-4 col-md-6">
				<form id="form_busca" class="form-horizontal" method="post">
					<div class="input-group h2">
						<input class="form-control" type="text" name="text_busca" id="pesquisa" placeholder="O que você está procurando?">
						<span class="input-group-btn">
							<button class="btn btn-success" type="submit">
								<span class="glyphicon glyphicon-search"></span>
							</button>
						</span>
					</div>
				</form>
				<div id="resultado" class="list-group">

				</div>
			</div>
			<div class="col-xs-4 col-sm-4 col-md-1">
				<a href="carrinho.php">
					<img src="img/carrinho.png" class="img-responsive" width="120" height="90"/>
					<div class="car_circle" style="top:20px;">
						<p class="car_itens"></p>
					</div>
				</a>

			</div>
		</div>
	</div>


	<div class="container">
		<div class="row">
			<div class="container" >
				<nav class="navbar navbar-inverse" role="navigation">
					<div class="container-fluid">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu" aria-expanded="false" aria-controls="navbar"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
						<div id="menu" class="navbar-collapse collapse">
							<ul class="nav navbar-nav navbar-left" style="font-size: 13pt; font-weight:700;">
								<?php
								$sql = "select * from categorias";
								$result = $con->bd->Execute($sql) or die("Erro na consulta: ".$con->bd->ErrorMsg());
								while(!$result->EOF){
									?>

									<li style="padding: 0 16.5px"><a href="categorias.php?cat=<?php echo $result->fields['cat_id']; ?>"><?php echo $result->fields['cat_descricao']; ?></a></li>

									<?php
									$result->MoveNext();
								} ?>
							</ul>
						</div>
					</div>
				</nav>
			</div>


			<div class="container" style="position:relative; top:-10px;">
				<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
				<div id="myCarousel" class="img-responsive img-rounded carousel slide" data-ride="carousel">
					<ol class="carousel-indicators">
						<li data-target="#myCarousel" data-slide-to="0" class="active" style="border:2px solid blue;"></li>
						<li data-target="#myCarousel" data-slide-to="1" style="border:2px solid blue;"></li>
						<li data-target="#myCarousel" data-slide-to="2" style="border:2px solid blue;"></li>
					</ol>
					<div class="carousel-inner" role="listbox">
						<div class="item active">
							<a href="categorias.php?cat=1"><img src="img/2.jpg"></a>
						</div>
						<div class="item">
							<a href="categorias.php?cat=2"><img src="img/1.jpg" width="100%"></a>
						</div>
						<div class="item">
							<a href="categorias.php?cat=6"><img src="img/4.jpg" height="100%" width="100%"></a>
						</div>
					</div>
					<a class="left carousel-control" href="#myCarousel" data-slide="prev">
						<span class="glyphicon glyphicon-chevron-left" ></span>
						<span class="sr-only">Anterior</span>
					</a>
					<a class="right carousel-control" href="#myCarousel" data-slide="next">
						<span class="glyphicon glyphicon-chevron-right"></span>
						<span class="sr-only">Próximo</span>
					</a>
				</div>
				</div>
				</div>
			</div>

			<div class="container marketing">
				<div class="col-xs-12 col-sm-12 col-md-12" style="border-bottom:2px solid black; text-transform: uppercase; padding:4px; padding-left: 20px;background-color:#11D400; margin-bottom:40px;">
					<h1 class="col-xs-12 col-sm-12 col-md-12 text-center" style="color:black; font-weight:600;">Produtos em destaque</h1>
				</div>

				<div class="row" style="margin-bottom:50px;">
					<?php
					$sql = "select * from produtos where pro_promocao <> 0 order by random() limit 6";
					$result = $con->bd->Execute($sql) or die("Erro na consulta dos produtos: ".$con->bd->ErrorMsg());
					while(!$result->EOF){
						?>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6" style="color:black; font-weight:600; display: inline-block; height: 450px;" >
							<img class="img-rounded" src="../../e-commerce/produtos/prod<?php echo $result->fields['pro_id'].".jpg"; ?>" alt="Imagem do <?php echo $result->fields['pro_descricao']; ?>" width="140" height="140">
							<h3><?php echo $result->fields['pro_descricao']; ?></h3>
							<?php if ($result->fields['pro_promocao']){ ?>
								<p>De: <s> <?php echo "R$ ".number_format($result->fields['pro_valor'],2,",","."); ?></s></p>
								<p>Por: <?php echo "R$ ".number_format($result->fields['pro_promocao'],2,",","."); ?></p>
							<?php } else { ?>
								<p>Preço: <?php echo "R$ ".number_format($result->fields['pro_valor'],2,",","."); ?></p>
							<?php } ?>
							<p><a class="btn btn-success" href="comprar.php?prod=<?php echo $result->fields['pro_id']; ?>" role="button"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Comprar</a></p>
							<p><a class="btn btn-primary" href="produtos.php?id=<?php echo $result->fields['pro_id']; ?>" role="button"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Ver Mais</a></p>
						</div>
						<?php
						$result->MoveNext();
					}
					$con->bd->Close();
					?>
				</div>
				<footer style="padding-left: 50px">
					<div class="row text-uppercase">
						<div class="col-md-4 col-xs-12 col-sm-4 text-justify">
							<h3 style="color:black; font-weight:600 ">Institucional</h3>
							<p><a href="sobre.php" style="text-decoration:none; color: black;">Sobre nós</a></p>
							<p><a href="contato.php"style="text-decoration:none; color: black;">Contato</a></p>
						</div>
						<div class="col-md-4 col-xs-12 col-sm-4 text-justify">
							<h3 style="color:black; font-weight:600 ">Dúvidas</h3>
							<p><a href="#"style="text-decoration:none; color: black;">Como comprar</a></p>
							<p><a href="#"style="text-decoration:none; color: black;">Pagamento</a></p>
							<p><a href="#"style="text-decoration:none; color: black;">Prazos e envio</a></p>
							<p><a href="#"style="text-decoration:none; color: black;">Garantia</a></p>
							<p><a href="#"style="text-decoration:none; color: black;">Trocas</a></p>
							<p><a href="#"style="text-decoration:none; color: black;">Segurança</a></p>
						</div>
						<div class="col-md-4 col-xs-12 col-sm-4 text-justify" >
							<h3 style="color:black; font-weight:600 ">Atendimento</h3>
							<p style="color: black;">Mande um e-mail para nós</p>
							<p><a href="#" style="text-decoration:none; color: black;">glm_vendas@glminfo.com.br</a></p>
							<p style="color: black;"><iframe src="https://www.facebook.com/plugins/follow.php?href=https%3A%2F%2Fwww.facebook.com%2Fleandro.cabeda&width=200&height=80&layout=standard&size=small&show_faces=true&appId" width="300" height="120" scrolling="no" frameborder="0" allowTransparency="true"></iframe></p>
						</div>
					</div>
					<p class="pull-right"><a href="#" style="list-style: none;color:black;text-decoration: none;">Voltar para Inicio da Página</a></p>
				</footer>
			</div>
		</div>
	</div>
	<script src="../js/jquery.min.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script>
	$(document).ready(function(){

		$("#pesquisa").keyup(function(){
			var pesquisa = $(this).val();
			if (pesquisa != '') {
				var dados = {
					palavra : pesquisa
				}
				$.ajax({
					url: "busca.php",
					type: "POST",
					data: dados,
				}).done(function(retorno) {
					$("#resultado").html(retorno);
				});
			}else{
				$("#resultado").html('');
			}
		});

	});

	<?php
	if ($linhas <= 0) {
		?>
		$(".car_itens").text("0");
		<?php
	}else{
		?>
		$(".car_itens").text("<?php echo $linhas; ?>");
		<?php
	}
	?>
	</script>
</body>

</html>
