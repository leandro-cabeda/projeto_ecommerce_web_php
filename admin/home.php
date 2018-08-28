<?php
session_start();

if (!isset($_SESSION['user_session'])) {
	header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Index</title>
	<link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="style.css" rel="stylesheet" media="screen">
	<link href="../css/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>
	<link href="../css/estilos.css" rel="stylesheet" type="text/css"/>
	<script src="../js/jquery.min.js" type="text/javascript"></script>
	<script src="../js/jquery.dataTables.min.js" type="text/javascript"></script>
	<script src="../js/dataTables.bootstrap.js" type="text/javascript"></script>
	<script src="../js/bootstrap.min.js" type="text/javascript"></script>
	<script src="../publica/bootstrap/js/jquery.mask.min.js" type="text/javascript"></script>
	<link href="../font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<style media="screen">
		#lg{ color: black; font-size: 13; padding: 13px; width:150px; height:200px; border: 2px solid black; padding-top: 45px; }
		#lg:hover{ background-color:#65FFE4; -webkit-transform: scale(1.08); -ms-transform: scale(1.08); transform: scale(1.08); }
	</style>
</head>
<body>
	<div id="wrapper" style="font-weight: 600; font-size: 12pt;">
		<nav class="navbar navbar-inverse navbar-fixed-top" style="background-color: black;" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Alternar navegação</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" style="color: white" href="home.php"><i class="fa fa-laptop" aria-hidden="true"></i>&nbsp;GML Informática</a>
				</div>
				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">
						<li><a href="index.php" style="color: white"><i class="fa fa-home" aria-hidden="true"></i>&nbsp;Home</a></li>
						<li><a href="?menu=clientes" style="color: white"><i class="fa fa-users" aria-hidden="true"></i>&nbsp;Clientes</a></li>
						<li><a href="?menu=categorias" style="color: white"><i class="fa fa-tags" aria-hidden="true"></i>&nbsp;Categorias</a></li>
						<li><a href="?menu=cidades" style="color: white"><i class="fa fa-building" aria-hidden="true"></i>&nbsp;Cidades</a></li>
						<li><a href="?menu=pedidos" style="color: white"><i class="fa fa-list-ul" aria-hidden="true"></i>&nbsp;Pedidos</a></li>
						<li><a href="?menu=produtos" style="color: white"><i class="fa fa-shopping-bag" aria-hidden="true"></i>&nbsp;Produtos</a></li>
						<li><a href="logout_publica.php" style="color: white"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;Ir para Página Publica</a></li>
						<li><a href="logout.php" style="color: white"><i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;Sair</a></li>
					</ul>
				</div>
			</div>
		</nav>
	</div>
	<?php
	if (isset($_REQUEST['menu'])) {
		$menu = $_REQUEST['menu'];
		include('modulos/' . $menu . '/datatable.html');
	}else{
		?>
		<div class="container-fluid text-center" style="margin: 40px 0 0 4%;">
			<div class="row">
				<h1 class="text-center" style="color: black; margin-bottom: 30px;"><b>Painel Administrativo</b></h1>
			</div>
			<div class="col-xs-6 col-sm-3 col-md-2" style="margin-right: 2%; margin-top: 2%">
				<a href="home.php?menu=clientes" class="btn btn-default" id="lg">
					<div class="row">
						<div class="col-xs-12 text-center">
							<i class="fa fa-users fa-5x" aria-hidden="true"></i>
						</div>
						<div class="col-xs-12 text-center">
							<b><p class="text-uppercase" style="margin-top:15px;">Clientes</p></b>
						</div>
					</div>
				</a>
			</div>
			<div class="col-xs-6 col-sm-3 col-md-2" style="margin-right:2%; margin-top: 2%">
				<a href="home.php?menu=categorias" class="btn btn-default" id="lg">
					<div class="row">
						<div class="col-xs-12 text-center">
							<i class="fa fa-tags fa-5x" aria-hidden="true"></i>
						</div>
						<div class="col-xs-12 text-center">
							<b><p class="text-uppercase" style="margin-top:15px;">Categorias</p></b>
						</div>
					</div>
				</a>
			</div>
			<div class="col-xs-6 col-sm-3 col-md-2" style="margin-right:2%; margin-top: 2%">
				<a href="home.php?menu=cidades" class="btn btn-default" id="lg">
					<div class="row">
						<div class="col-xs-12 text-center">
							<i class="fa fa-building fa-5x" aria-hidden="true"></i>
						</div>
						<div class="col-xs-12 text-center">
							<b><p class="text-uppercase" style="margin-top:15px;">Cidades</p></b>
						</div>
					</div>
				</a>
			</div>
			<div class="col-xs-6 col-sm-3 col-md-2" style="margin-right:2%; margin-top: 2%">
				<a href="home.php?menu=pedidos" class="btn btn-default" id="lg">
					<div class="row">
						<div class="col-xs-12 text-center">
							<i class="fa fa-list-ul fa-5x" aria-hidden="true"></i>
						</div>
						<div class="col-xs-12 text-center">
							<b><p class="text-uppercase" style="margin-top:15px;">Pedidos</p></b>
						</div>
					</div>
				</a>
			</div>
			<div class="col-xs-6 col-sm-3 col-md-2" style="margin-right:2%; margin-top: 2%">
				<a href="home.php?menu=produtos" class="btn btn-default" id="lg">
					<div class="row">
						<div class="col-xs-12 text-center">
							<i class="fa fa-shopping-bag fa-5x" aria-hidden="true"></i>
						</div>
						<div class="col-xs-12 text-center">
							<b><p class="text-uppercase" style="margin-top:15px;">Produtos</p></b>
						</div>
					</div>
				</a>
			</div>
		</div>
		<?php
	}
	?>
	<div id="wrapper" style="font-weight: 600; font-size: 12pt; ">
		<nav class="navbar navbar-fixed-bottom" style="background-color: black;" role="navigation">
			<div class="container-fluid">
				<p class="text-center" style="color: white; padding-top: 1%">Copyright © 2017 - GML Informática</p>
			</div>
		</nav>
	</div>
</body>
</html>
