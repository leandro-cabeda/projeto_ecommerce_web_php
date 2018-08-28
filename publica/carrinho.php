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
	<script src="js/jquery-3.1.1.min.js" type="text/javascript"></script>
	<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="bootstrap/js/jquery.mask.min.js" type="text/javascript"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		$('.cep').mask('00000-000');
	});
	</script>
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
					<h1 class="col-xs-12 col-sm-12 col-md-12 text-center" style="color:black; font-weight:600;">Meu Carrinho</h1>
				</div>

				<div class="row" style="margin-bottom:55px;">
					<div id="tabela" class="col-xs-12 col-md-12">

						<table id="products-table"class="table table-striped">
							<thead>
								<tr>
									<th>Produto</th>
									<th>Quantidade</th>
									<th>valor Unitário</th>
									<th>Total</th>
									<th>Opção</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$sql = "select * from produtos p, cesta_itens ci where p.pro_id = ci.pro_id and ces_sessao = '$sessao'";
								$res = $con->bd->Execute($sql) or die("Erro na consulta: ".$con->bd->ErrorMsg());
								$total = 0;
								$peso_total = 0;
								while(!$res->EOF){
									$_SESSION['array_carrinho'] = $res->fields['pro_id'];
									if ($res->fields['pro_promocao']) {
										$pro_preco = $res->fields['pro_promocao'];
									}else{
										$pro_preco = $res->fields['pro_valor'];
									}
									$total += $pro_preco * $res->fields['cite_qtd'];
									$peso_total += $res->fields['pro_peso'];
									?>
									<tr id="linha_prod<?php echo $res->fields['pro_id']; ?>">
										<td><?php echo $res->fields['pro_descricao']; ?></td>
										<form method="post" id="form_alterar">
											<td>
												<input type="number" id="input_alt<?php echo $res->fields['pro_id']; ?>" name="quantidade" min="1" max="<?php echo $res->fields['pro_estoque']?>"value="<?php echo $res->fields['cite_qtd'];?>">
												&nbsp;
												<button class="btn btn-success" data-id='<?php echo $res->fields['pro_id']; ?>' data-action='edit' id="alterar" name="alterar" value="Alterar">Alterar</button>
											</td>
											<td>R$ <?php echo number_format($pro_preco,2,",","."); ?></td>
											<td id="preco_total<?php echo $res->fields['pro_id']; ?>">R$ <?php echo number_format($pro_preco * $res->fields['cite_qtd'],2,",","."); ?></td>
										</form>
										<td><p><a class="btn btn-danger" data-id='<?php echo $res->fields['pro_id']; ?>' data-action='del' id="deletar" role="button"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Remover</a></p></td>
									</tr>
									<?php
									$res->MoveNext();
								}
								$linhas = $res->RecordCount();
								$con->bd->Close();
								?>
							</tbody>
							<tr>
								<td colspan="5" class="text-right valor_total" name="total">Total da Compra: R$ <?php echo number_format($total,2,",","."); ?></td>
							</tr>
						</table>
						<?php	if ($linhas > 0) { ?>

							<form class="form-inline" id="form_cep" method="POST">
								<input type="text" class="cep form-control" name="cep" style="width: 12%" id="cep" maxlength="9"> &nbsp;&nbsp;
								<input type="hidden" name="peso" value="<?php echo $peso_total; ?>">
								<button type="submit" class="btn btn-primary ">Calcular CEP</button>
							</form>
							<div id="msg"></div>
							<?php if (!$_SESSION['usu_codigo']) { ?>
								<p><a class="btn btn-lg btn-success" id="btn-finaliza" style="float: right" href="login.php" role="button">Finalizar Pedido &raquo;</a></p>
							<?php	} else { ?>
								<form id="form_finaliza" method="GET" action="finaliza-pedido.php">
									<input type="text" name="frete" id="valor_frete" style="display: none">
									<input type="text" name="total" id="valor_total" style="display: none">
									<button type="submit" id="btn-finaliza" class="btn btn-lg btn-success" style="float: right" >Finalizar Pedido &raquo;</button>
								</form>
							<?php }
						} else {
							?>
							<script type="text/javascript">
							$(".car_itens").text("0");
							$("table").css("display", "none");
							</script>
							<p class="alert alert-danger text-center"><b>Você ainda não adicionou produtos no carrinho! </b></p>
							<br><br>
							<a href="index.php" class="btn btn-warning"><b>Voltar para página principal</b></a>
						<?php	} ?>
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
			<script>

			$().ready(function() {
				var acao = "";

				function mascaraValor(valor) {
					valor = valor.toString().replace(/\D/g,"");
					valor = valor.toString().replace(/(\d)(\d{8})$/,"$1.$2");
					valor = valor.toString().replace(/(\d)(\d{5})$/,"$1.$2");
					valor = valor.toString().replace(/(\d)(\d{2})$/,"$1,$2");
					return valor
				}

				function calculaCEP(div){
					if (div != 0) {
						var array = $('#msg').html().split("<br>");
						array[2] = array[2].replace(/[^0-9]/g,'');
						var valor_frete = array[2];
						var valor_frete = parseInt(valor_frete);
						valor_frete = valor_frete.toFixed(0).replace(".", ",").replace(/(\d)(?=(\d{2})+(?!\d))/g, "$1,");
						valor_frete  = parseFloat(valor_frete.replace(",", "."));
						$("#valor_frete").attr("value", valor_frete);
						var linhas = $('#products-table td:nth-child(5)');
						var total = 0;
						for (var i = 0; i < linhas.length; i++) {
							var valor = $(linhas[i]).text();
							valor = parseInt(valor.split(/\D+/).join(""), 10);
							total += valor;
						}
						$(".valor_total").attr("value", total);
						$("#valor_total").attr("value", total);
						var total_compra_frete = total + parseInt(valor_frete.toString().split(/\D+/).join(""), 10);
						var total_compra = mascaraValor(total);
						$(".valor_total").text("Total da Compra: R$ "+total_compra);
						total_compra_frete = mascaraValor(total_compra_frete);
						$(".valor_total").text("Total da Compra com frete: R$ "+total_compra_frete);
					}
				}

				$(document).on("click", "#alterar", function(e){
					e.preventDefault();
					var id = $(this).data("id");
					var action = $(this).data("action");
					if(action == "edit"){
						acao = action;
						editProduto(id);
					}
				});

				$(document).on("click", "#deletar", function(e){
					e.preventDefault();
					var tot_carrinho = $(".car_itens").text();
					var id = $(this).data("id");
					var action = $(this).data("action");
					if(action == "del"){
						var line = $(this).parent().parent().parent(); //tr
						var table = $(this).parent().parent().parent().parent().parent(); //table
						$.ajax({
							url: "excluir-produto.php?ref="+id,
							type: "GET",
							dataType: "TEXT",
							success: function(retorno){
								line.remove();
								var linhas = $('#products-table td:nth-child(5)');
								var total = 0;
								for (var i = 0; i < linhas.length; i++) {
									var valor = $(linhas[i]).text();
									valor = parseInt(valor.split(/\D+/).join(""), 10);
									total += valor;
								}
								$(".valor_total").attr("value", total);
								$("#valor_total").attr("value", total);
								var total_compra = mascaraValor(total);
								$(".valor_total").text("Total da Compra: R$ "+total_compra);
								if ($("#msg") !== "") {
									var div = $('#msg').html().length;
									calculaCEP(div);
								}
								if (tot_carrinho > 0) {
									tot_carrinho -= 1;
									$(".car_itens").text(tot_carrinho);
									if (tot_carrinho == 0) {
										$(".car_itens").text("0");
										table.remove();
										$("#form_cep").remove();
										$("#btn-finaliza").remove();
										$("#tabela").html('<p class="alert alert-danger text-center"><b>Você removeu todos os produtos do seu carrinho! </b></p><br><br><a href="index.php" class="btn btn-warning"><b>Voltar para página principal</b></a>');
									}
								}
							},
							error: function(xhr, textStatus,error){
								console.log("Erro ao processar requisição");
							}
						});
					}
				});

				function editProduto(id){
					var nome = "#input_alt"+id;
					var qtd = $(nome).val();
					$.ajax({
						url: "alterar.php?qtd="+qtd+"&ref="+id,
						type: "GET",
						dataType: "JSON",
						success : function(retorno) {
							$("#preco_total"+id).text(retorno.valor_total);
							var linhas = $('#products-table td:nth-child(5)');
							var total = 0;
							for (var i = 0; i < linhas.length; i++) {
								var valor = $(linhas[i]).text();
								valor = parseInt(valor.split(/\D+/).join(""), 10);
								total += valor;
							}
							$(".valor_total").attr("value", total);
							$("#valor_total").attr("value", total);
							var total_compra = mascaraValor(total);
							$(".valor_total").text("Total da Compra: R$ "+total_compra);
							if ($("#msg") !== "") {
								var div = $('#msg').html().length;
								calculaCEP(div);
							}
						},
						error: function(xhr, testStatus, error) {
							console.log("Erro ao processar requisição");
						}
					});
				}

				$("#form_finaliza").on("submit", function(e) {
					e.preventDefault();
					if ($(".cep").val() == "") {
						$("#msg").html("<br><b>Por favor, informe o CEP da sua cidade!</b>");
						$("#btn-finaliza").prop('disabled',true);
					}else{
						$(this).unbind('submit').submit();
					}
				});

				$("#form_cep").on("submit", function(e) {
					e.preventDefault();
					var dados = $("#form_cep").serialize(); // pega os dados do formulário
					if ($(".cep").val() == "") {
						$("#msg").html("<br><b>Por favor, informe o CEP da sua cidade!</b>");
						$("#btn-finaliza").prop('disabled',true);
					}else{
						$("#btn-finaliza").prop('disabled',false);
						$.ajax({
							type: "POST",
							url: "cep.php",
							data: dados,
						}).done(function(retorno) {
							$("#msg").html("<br>"+retorno);
							var div = $('#msg').html().length;
							calculaCEP(div);
						}).fail(function(xhr, status, errorThrown){ //callback de tratamento de erro
							console.log("Erro ao solicitar requisição!");
						});
					}
				});

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
