<?php
session_start();
require("../adodb5/adodb.inc.php");
require("../conecta.php");
$con = new conecta();
error_reporting(E_ALL ^ E_NOTICE);
$sessao = $_SESSION['id'];

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
	<link href="../css/style.css" rel="stylesheet">
	<script src="js/jquery-3.1.1.min.js" type="text/javascript"></script>
	<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<link rel="stylesheet" href="css/jquery-ui.css">
	<script src="js/jquery.ui.datepicker-pt-BR.js"></script>
	<script src="bootstrap/js/jquery.mask.min.js" type="text/javascript"></script>
	<script src="js/script.js"></script>
	<style media="screen">
	.modal {
		text-align: center;
		padding: 0!important;
	}
	.modal:before {
		content: '';
		display: inline-block;
		height: 100%;
		vertical-align: middle;
		margin-right: -4px;
	}
	.modal-dialog {
		display: inline-block;
		text-align: left;
		vertical-align: middle;
	}
	</style>
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
					<h1 class=" title_form col-xs-12 col-sm-12 col-md-12 text-center" style="color:black; font-weight:600;">Cadastro de Cliente</h1>
				</div>
				<div class="container">
					<div class="row">
					<aside role="complementary" class="col-md-8 col-md-offset-1">
						<form  enctype="multipart/form-data" method="POST" id="form_cad" class="form-horizontal">
							<div class="row">
								<div class="form-group" style="color: black; font-weight:bold">
									<p style="font-weight:600;font-size:22pt;"  id="campos">*Todos os Campos são Obrigatório</p>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12  col-md-4">
									<div class="form-group">
										<label for="email" class="control-label" style="font-size:18px; color:black; font-weight:bold;">E-mail:*</label>
										<input type="email" style="width:200px;" required placeholder="exemple@hotmail.com" name="email" id="email" class="form-control">
									</div>
									<div class="form-group">
										<label for="senha" class="control-label" style="font-size:18px;color:black; font-weight:bold;">Senha:*</label>
										<input type="password" style="width:200px;" required class="form-control" name="senha" placeholder="***********" id="senha">
									</div>
									<div class="form-group">
										<label for="cpf" class="control-label" style="font-size:18px;color:black; font-weight:bold;">CPF:*</label>
										<input type="text" style="width:200px;" required placeholder="000.000.000-00" name="cpf" id="cpf" class="form-control">
									</div>
									<div class="form-group">
										<label for="cep" class="control-label" style="font-size:18px;color:black; font-weight:bold;">CEP:*</label>
										<input type="text" style="width:200px;" required placeholder="00000-000" name="cep" id="cep" class="form-control">
									</div>
								</div>
								<div class="col-xs-12  col-md-4">
									<div class="form-group">
										<label for="fone1" class="control-label" style="font-size:18px;color:black; font-weight:bold;">Telefone 1:*</label>
										<input type="text" style="width:200px;" required placeholder="(00) 00000-0000" name="fone1" id="fone1" class="form-control">
									</div>

									<div class="form-group">
										<label for="fone2" class="control-label" style="font-size:18px;color:black; font-weight:bold;">Telefone 2:*</label>
										<input type="text" style="width:200px;" required placeholder="(00) 00000-0000" name="fone2" id="fone2" class="form-control">
									</div>


									<div class="form-group">
										<label for="nome" class="control-label" style="font-size:18px;color:black; font-weight:bold;">Nome:*</label>
										<input type="text" style="width:200px;" required placeholder="Digite seu nome" name="nome" id="nome" class="form-control">
									</div>

									<div class="form-group">
										<label for="endereco" class="control-label" style="font-size:18px;color:black; font-weight:bold;">Endereço:*</label>
										<input type="text" style="width:200px;" required placeholder="Digite seu endereço" name="endereco" id="endereco" class="form-control">
									</div>

								</div>
								<div class="col-xs-12  col-md-4">
									<div class="form-group">
										<label class="control-label" style="font-size:18px;color:black; font-weight:bold;">Cidade:*</label>
										<div class="cid_id" style="width:200px;"></div>
									</div>


									<div class="form-group">
										<label for="bairro" class="control-label" style="font-size:18px;color:black; font-weight:bold;">Bairro:*</label>
										<input type="text" style="width:200px;" required placeholder="Digite seu bairro" name="bairro" id="bairro" class="form-control">
									</div>
									<div class="form-group">
										<label class="control-label" style="font-size:18px;color:black; font-weight:bold;">* Data de nascimento:</label>
										<input type="text" id="data"  placeholder="00/00/0000" style="width:200px;" name="dtNasc" required class="data form-control">
									</div>

									<div class="form-group">
										<label class="control-label" style="font-size:18px;color:black; font-weight:bold;">Sexo:*</label><br>
										Masculino&nbsp;&nbsp;<input type="radio" value="M" name="sexo" id="m">&nbsp;&nbsp;
										Feminino&nbsp;&nbsp;<input type="radio" value="F" name="sexo" id="f">
									</div>

								</div>
							</div>
							<div class="row">
								<div class="col-xs-12  col-md-4">
									<div class="form-group">
										<input type="submit" value="Cadastrar" class="btn btn-success">
										&nbsp;&nbsp;&nbsp;<input type="reset" value="Limpar" class="btn btn-primary">
									</div>
								</div>

								<div class="col-xs-6  col-md-4 col-md-offset-4" style="margin-top:-20px;">

									<div class="form-group">
										<label class="control-label" style="font-size:18px;color:black; font-weight:bold;">Mala Direta:*</label><br>
										Sim&nbsp;&nbsp;<input type="radio" value="S" name="mala" id="mala">&nbsp;&nbsp;
										Não&nbsp;&nbsp;<input type="radio" value="N" name="mala" id="mala">&nbsp;&nbsp;
									</div>
								</div>
							</div>
						</div>
					</form>
					<div id="msg"> </div>
				</aside>
			</div><br><br>

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

			<div id="myModal" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title2"></h4>
						</div>
						<div class="modal-body">
							<p id="msgModal"></p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
						</div>
					</div>

				</div>
			</div>

			<script>
			var acao="";

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

				<?php
				if (!$_SESSION['usu_codigo']) {
					?>

					var acao="";

					$(document).on("submit", "#form_cad", function(e){
						acao = "add";
						e.preventDefault();
						save();
					});

					function save(){
						var form = $("#form_cad").serialize();
						console.log("Form: "+form);
						$.ajax({
							url: "clientes.php?acao="+acao,
							type: "POST",
							data: form,
						}).done(function(retorno) {
							if (retorno == "ok") {
								$("#myModal").modal("show");
								$(".modal-title2").html("<h2 class='text-center'><b> Informação </b></h2>");
								$("#msgModal").html("<div class='alert alert-success text-center'><b> Cliente foi cadastrado com sucesso! </b></div>");
								setTimeout('window.location.href="login.php"', 2000);
							} else {
								$("#myModal").modal("show");
								$(".modal-title2").html("<h2 class='text-center'><b> Informação </b></h2>");
								$("#msgModal").html("<div class='alert alert-danger text-center'><b> Erro ao cadastrar produto! </b></div>");
							}
						});
					}
					<?php
				}else {
					?>
					$(document).ready(function(){
						acao = "edit";
						var id = <?php echo $_SESSION['usu_codigo']; ?>;
						$("#form_cad")[0].reset();
						$.ajax({
							url: "clientes.php?acao=getCliente&id="+id,
							type: "GET",
							dataType: "JSON",
							success : function (retorno){
								$('[name="id"]').val(retorno.id);
								$('[name="nome"]').val(retorno.nome);
								$('[name="email"]').val(retorno.email);
								$('[name="endereco"]').val(retorno.endereco);
								$('[name="senha"]').val(retorno.senha);
								$('[name="fone1"]').val(retorno.fone1);
								$('[name="fone2"]').val(retorno.fone2);
								$('[name="cep"]').val(retorno.cep);
								$('[name="cpf"]').val(retorno.cpf);
								$('[name="bairro"]').val(retorno.bairro);
								$('[name="dtNasc"]').val(retorno.dtNasc);
								$('[name="cidade"]').val(retorno.cidade);
								if (retorno.mala === 'S') {
									$('input:radio[name="mala"][value="S"]').prop('checked',true);
								}else {
									$('input:radio[name="mala"][value="N"]').prop('checked',true);
								}
								if (retorno.sexo === 'F') {
									$('input:radio[name="sexo"][value="F"]').prop('checked',true);
								}else {
									$('input:radio[name="sexo"][value="M"]').prop('checked',true);
								}
								$(".title_form").text("Alterar Cadastro");
								$(".btn-limpar").remove();
								$("#btn-login").text("Alterar Cadastro");
							},
							error: function(xhr, textStatus,error){
								alert("erro ao processar requisição");
							}
						});
						$(document).on("submit", "#form_cad", function(e){
							e.preventDefault();
							save();
						});
						function save(){
							var form = $("#form_cad").serialize();
							$.ajax({
								url: "clientes.php?acao="+acao,
								type: "POST",
								data: form,
							}).done(function(retorno) {
								if (retorno == "ok") {
									$("#myModal").modal("show");
									$(".modal-title2").html("<h2 class='text-center'><b> Informação </b></h2>");
									$("#msgModal").html("<div class='alert alert-success text-center'><b> Cliente foi alterado com sucesso! \n\
									</b></div>");
								} else {
									$("#myModal").modal("show");
									$(".modal-title2").html("<h2 class='text-center'><b> Informação </b></h2>");
									$("#msgModal").html("<div class='alert alert-success text-center'><b> Erro ao alterar cliente! \n\
									</b></div>");
								}
							});
						}
					});
					<?php
				}
				?>

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
				$('#cep').mask('00000-000');
				$('#cpf').mask('000.000.000-00');
				$('#fone1').mask('(00) 00000-0000');
				$('#fone2').mask('(00) 00000-0000');
				$('#data').mask('00/00/0000');
				$('.data').datepicker();
			});
			</script>

		</body>

		</html>
