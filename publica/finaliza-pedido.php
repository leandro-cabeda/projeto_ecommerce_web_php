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

if (isset($_GET['frete'])) {
	$valor_frete = filter_input(INPUT_GET, 'frete');
	$total = filter_input(INPUT_GET, 'total');
	$tam = strlen($total);
	$total_format = substr_replace($total, ".", $tam-2).substr($total, $tam-2); //montando a string com o ponto
}else {
	echo "erro";
}
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
	<script src="js/script.js"></script>
	<link href="css/style_finaliza_ped.css" rel="stylesheet">
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

								<li style="padding: 0 16.5px"><a href="#"><?php echo $result->fields['cat_descricao']; ?></a></li>

								<?php
								$result->MoveNext();
							} ?>
						</ul>
					</div>
				</div>
			</nav>
		</div>
	</div>

	<div class="container marketing" >
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12" style="border-bottom:2px solid black; text-transform: uppercase; padding:4px; padding-left: 20px;background-color:#11D400; margin-bottom:40px;">
				<h1 class="col-xs-12 col-sm-12 col-md-12 text-center" style="color:black; font-weight:600;">Finalizar Pedido</h1>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<section class="col-md-12" style="width: 95%;">
					<div class="wizard">
						<div class="wizard-inner">
							<ul class="nav nav-tabs" role="tablist">
								<li role="presentation" class="active">
									<a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Dados da Entrega">
										<span class="round-tab">
											<i class="glyphicon glyphicon glyphicon-home"></i>
										</span>
									</a>
								</li>
								<li role="presentation" class="disabled">
									<a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Itens do Pedido">
										<span class="round-tab">
											<i class="glyphicon glyphicon-shopping-cart"></i>
										</span>
									</a>
								</li>
								<li role="presentation" class="disabled">
									<a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="Pagamento">
										<span class="round-tab">
											<i class="glyphicon glyphicon-usd"></i>
										</span>
									</a>
								</li>
								<li role="presentation" class="disabled">
									<a href="#complete" data-toggle="tab" aria-controls="complete" role="tab" title="Confirmar">
										<span class="round-tab">
											<i class="glyphicon glyphicon-ok"></i>
										</span>
									</a>
								</li>
							</ul>
						</div>
						<form role="form" method="POST" id="form_confirma" novalidate>
							<div class="tab-content">
								<div class="tab-pane active" role="tabpanel" id="step1" style="text-indent: 30px">
									<h3>Dados da Entrega</h3>
									<br>
									<?php
									$cli_id = $_SESSION['usu_codigo'];
									$sql = "select * from clientes cli, cidades c where cli_id = $cli_id and cli.cid_id = c.cid_id";
									$res = $con->bd->Execute($sql) or die("Erro na consulta: ".$con->bd->ErrorMsg());
									?>
									<p><b>Nome:</b> <?php echo $res->fields['cli_nome']; ?></p>
									<p><b>Endereço:</b> <?php echo $res->fields['cli_endereco']; ?></p>
									<p><b>Bairro:</b> <?php echo $res->fields['cli_bairro']; ?></p>
									<p><b>CEP:</b> <?php echo $res->fields['cli_cep']; ?></p>
									<p><b>Cidade:</b> <?php echo $res->fields['cid_nome']." - ".$res->fields['cid_uf']; ?></p><br>
									<p><a href="cadastro.php" style="text-decoration: none; color: #166479"><b>Alterar dados</b></a></p>
									<ul class="list-inline pull-right">
										<li><button type="button" class="btn btn-primary next-step">Próximo</button></li>
									</ul>
								</div>

								<div class="tab-pane" role="tabpanel" id="step2">
									<h3>Itens do Pedido</h3>
									<br>
									<table class="table table-striped">
										<thead>
											<tr>
												<th>Produto</th>
												<th>Quantidade</th>
												<th>valor Unitário</th>
												<th>Total</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$sql = "select * from produtos p, cesta_itens ci where p.pro_id = ci.pro_id and ces_sessao = '$sessao'";
											$result = $con->bd->Execute($sql) or die("Erro na consulta: ".$con->bd->ErrorMsg());
											while(!$result->EOF){
												if ($result->fields['pro_promocao']) {
													$pro_preco = $result->fields['pro_promocao'];
												}else{
													$pro_preco = $result->fields['pro_valor'];
												}
												?>
												<tr>
													<td><?php echo $result->fields['pro_descricao']; ?></td>
													<td><?php echo $result->fields['cite_qtd']; ?></td>
													<td>R$ <?php echo number_format($pro_preco,2,",","."); ?></td>
													<td>R$ <?php echo number_format($pro_preco * $result->fields['cite_qtd'],2,",","."); ?></td>
												</tr>
												<?php
												$result->MoveNext();
											}
											$con->bd->Close();
											?>
										</tbody>
									</table>
									<ul class="list-inline pull-right">
										<li><button type="button" class="btn btn-default prev-step">Voltar</button></li>
										<li><button type="button" class="btn btn-primary next-step">Próximo</button></li>
									</ul>
								</div>
								<div class="tab-pane" role="tabpanel" id="step3">
									<h3>Pagamento</h3><br>
									<p><b>Total da Compra:</b> <?php echo "R$ ".number_format($total_format,2,",","."); ?></p>
									<p><b>Valor do Frete:</b> <?php echo "R$ ".number_format($valor_frete,2,",",".");; ?></p>
									<p><b>Total do Pedido:</b> <?php echo "R$ ".number_format(($total_format + $valor_frete),2,",","."); ?></p>
									<br>
									<label class="control-label" style="font-weight:bold; margin-right: 10px">Tipo de frete:</label>
									<input type="radio" value="1" name="tipo_frete" id="tipo_frete" checked>&nbsp;PAC
									<br>
									<label class="control-label" style="font-weight:bold; margin-right: 10px">Tipo de Pagamento: </label>
									<input type="radio" value="B" name="tipopag" id="tipopag1">&nbsp;
									<label style="margin-right: 10px" for="tipopag1">Boleto</label>
									<input type="radio" value="C" name="tipopag" id="tipopag2">&nbsp;
									<label style="margin-right: 10px" for="tipopag2">Cartão</label>
									<span id="msg"></span>
									<ul class="list-inline pull-right">
										<li><button type="button" class="btn btn-default prev-step">Voltar</button></li>
										<li><button type="button" class="btn btn-primary next-step">Próximo</button></li>
									</ul>
								</div>
								<div class="tab-pane" role="tabpanel" id="complete">
									<h3>Confirmar Pagamento</h3>
									<br>
									<p><b>Total do Pedido:</b> <?php echo "R$ ".number_format(($total_format + $valor_frete),2,",","."); ?></p>
									<input type="checkbox" value="1" name="ped_status" id="ped_status">&nbsp;
									<label for="ped_status"> <?php echo $res->fields['cli_nome'] ?>, você confirma os dados para solicitarmos seu pedido?</label>
									<span id="msg1"></span>
									<input type="number" name="cli_id" value="<?php echo $res->fields['cli_id']; ?>" style="display:none;">
									<input type="text" name="cli_endereco" value="<?php echo $res->fields['cli_endereco']; ?>" style="display:none;">
									<input type="text" name="cli_bairro" value="<?php echo $res->fields['cli_bairro']; ?>" style="display:none;">
									<input type="text" name="cli_cep" value="<?php echo $res->fields['cli_cep']; ?>" style="display:none;">
									<input type="text" name="cid_id" value="<?php echo $res->fields['cid_id']; ?>" style="display:none;">
									<input type="text" name="total" value="<?php echo $total_format ?>" style="display:none;">
									<input type="text" name="frete" value="<?php echo $valor_frete ?>" style="display:none;">
									<ul class="list-inline pull-right">
										<button type="submit" id="btn-finaliza" class="btn btn-success btn-lg">Concluir Pedido</button>
									</ul>
								</div>
							</div>
						</form>
					</div>
				</section>
			</div>
			<div id="msg2">	</div>
		</div>
	</div>

	<div class="container marketing">
		<div class="row">
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

	<script>
	$(document).ready(function(){

		$(document).on("submit", "#form_confirma", function(e){
			e.preventDefault();
			var dados = $("#form_confirma").serialize();
			if (! $("input[type='radio'][name='tipopag']").is(':checked') ) {
				prevTab($('.wizard .nav-tabs li.active'));
				$("#msg").html('<div class="alert alert-danger text-center"\n\
				<span class="text-center"> </span>' + "Por favor, selecione o tipo de pagamento!" + '</div>');
				return false;
			}
			else if( !$("input[type='checkbox'][name='ped_status']").is(':checked')){
				$("#msg1").html('<div class="alert alert-danger text-center"\n\
				<span class="text-center"> </span>' + "Por favor, selecione a caixa para confirmar seu pedido!" + '</div>');
				return false;
			}else{
				$.ajax({
					type: "POST",
					url: "finaliza-process.php",
					data: dados,
					beforeSend: function() {
						$("#btn-finaliza").css("visibility", "hidden");
					}
				}).done(function(retorno) {
					if (retorno == "ok") {
						$("#msg2").fadeIn(1000, function() {
							$("#msg2").html('<div class="alert alert-success"\n\
							<span></span>' + "Pedido realizado com sucesso!<br><br>Obrigado por comprar na GLM Informática!"+ '</div>');
						});
						setTimeout('window.location.href="index.php"', 2000); //reidireciona para home.php após 2s
					} else {
						$("#msg2").fadeIn(1000, function() {
							$("#msg2").html('<div class="alert alert-danger"\n\
							<span></span>' + "Erro ao solicitar pedido!" + '</div>');
						});
					}
				});
			}
		});

		$('.nav-tabs > li a[title]').tooltip();
		$('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
			var $target = $(e.target);
			if ($target.parent().hasClass('disabled')) {
				return false;
			}
		});
		$(".next-step").click(function (e) {
			var $active = $('.wizard .nav-tabs li.active');
			$active.next().removeClass('disabled');
			nextTab($active);
		});
		$(".prev-step").click(function (e) {
			var $active = $('.wizard .nav-tabs li.active');
			prevTab($active);
		});
		$('#myTabs a').click(function (e) {
			e.preventDefault()
			$(this).tab('show');
			if ($("")) {

			}
		});
		function nextTab(elem) {
			$(elem).next().find('a[data-toggle="tab"]').click();
		}
		function prevTab(elem) {
			$(elem).prev().find('a[data-toggle="tab"]').click();
		}

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
	});
</script>

</body>

</html>
