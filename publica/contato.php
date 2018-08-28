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
	<link href="../css/style_contact.css" rel="stylesheet">
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

			<div class="container marketing">
				<div class="col-xs-12 col-sm-12 col-md-12" style="border-bottom:2px solid black; text-transform: uppercase; padding:4px; padding-left: 20px;background-color:#11D400; margin-bottom:40px;">
					<h1 class="col-xs-12 col-sm-12 col-md-12 text-center" style="color:black; font-weight:600;">Fale Conosco</h1>
				</div>

				<div class="row" style="margin-bottom:55px;">
					<div class="col-xs-6 col-md-4 col-xs-offset-1 col-md-offset-1" id="panel">
						<h2>Contate-nos</h2>
						<form>
							<div class="group">
								<input type="text" required>
								<span class="highlight"></span>
								<span class="bar"></span>
								<label>Nome</label>
							</div>
							<div class="group">
								<input type="email" required>
								<span class="highlight"></span>
								<span class="bar"></span>
								<label>E-mail</label>
							</div>
							<div class="group">
								<input type="text" required>
								<span class="highlight"></span>
								<span class="bar"></span>
								<label>Mensagem</label>
							</div>
							<div class="group">
								<center> <button type="submit" class="btn btn-success btn-lg" style="font-weight: 600;">Enviar <span class="glyphicon glyphicon-send"></span></button></center>
							</div>
						</form>
					</div>

					<div class="col-xs-6 col-md-6 col-xs-offset-1 col-md-offset-1">
						<h1 class="text-center" style="color:black;font-weight:600;">Localidade</h1>
						<iframe src="https://www.google.com/maps/embed?pb=!1m23!1m12!1m3!1d5910.0522175514!2d-52.41982883216521!3d-28.26382984919614!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m8!3e6!4m0!4m5!1s0x94e2c09e3fe0c317%3A0x7aed940e24c087c3!2sRua+Lava+P%C3%A9s%2C+526-690+-+Boqueir%C3%A3o%2C+Passo+Fundo+-+RS%2C+99025-120%2C+Brasil!3m2!1d-28.2633805!2d-52.4211956!5e0!3m2!1spt-BR!2sbr!4v1478895236619"  frameborder="0" style="height: 27em;width:38em;" allowfullscreen></iframe>
					</div>
				</div>
				<!-- Rodapé -->
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

			<script src="../js/jquery.min.js"></script>
			<script src="../js/bootstrap.min.js"></script>
			<script>
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
