var acao = "";
var table;

$(document).ready(function () {

	load_cidade();

	table = $('#clientes').DataTable({
		"ajax": 'modulos/clientes/clientes.php',
		"oLanguage": {
			"sEmptyTable": "Nenhum registro encontrado",
			"sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
			"sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
			"sInfoFiltered": "(Filtrados de _MAX_ registros)",
			"sInfoPostFix": "",
			"sInfoThousands": ".",
			"sLengthMenu": "_MENU_ resultados por página",
			"sLoadingRecords": "Carregando...",
			"sProcessing": "Processando...",
			"sZeroRecords": "Nenhum registro encontrado",
			"sSearch": "Pesquisar",
			"oPaginate": {
				"sNext": "Próximo",
				"sPrevious": "Anterior",
				"sFirst": "Primeiro",
				"sLast": "Último"
			},
			"oAria": {
				"sSortAscending": ": Ordenar colunas de forma ascendente",
				"sSortDescending": ": Ordenar colunas de forma descendente"
			}
		}
	});

	$(document).on('click', '#addCliente', function () {
		abreModal();
	});

	function abreModal() {
		acao = "add";
		$("#form")[0].reset();
		$("#modal_form").modal('show');
		$(".modal-title").html("<h2 class='text-center'><b>Adicionar Cliente</b></h2>");
	}

	$(document).on('submit', '#form', function (e) {
		e.preventDefault();
		save();
	});

	$(document).on('click', 'button.btn', function () {
		var id = $(this).data('id');
		var action = $(this).data('action');
		if (action == "edit") {
			acao = action;
			editCliente(id);
		} else if (action == "del") {
			acao = action;
			delCliente(id);
		}
	});

	function save() {
		var url = "modulos/clientes/clientes.php?acao=" + acao;
		var form = new FormData($("#form")[0]);
		$.ajax({
			url: url,
			type: "POST",
			data: form,
			dataType: "text",
			processData: false,
			contentType: false,
		}).done(function(retorno){
			if(retorno == "ok"){
				$("#modal_form").modal('hide');
				table.ajax.reload(null, false);
				$("#myModal").modal("show");
				$(".modal-title2").html("<h2 class='text-center'><b> Informação </b></h2>");
				if (acao == "edit") {
					$("#msgModal").html("<div class='alert alert-success text-center'><b> Cliente foi alterado com sucesso! </b></div>");
				}else {
					$("#msgModal").html("<div class='alert alert-success text-center'><b> Cliente foi cadastrado com sucesso! </b></div>");
				}
			}else if (retorno == "e-mail ja existe") {
				$("#modal_form").modal('hide');
				$("#myModal").modal("show");
				$(".modal-title2").html("<h2 class='text-center'><b> Informação </b></h2>");
				$("#msgModal").html("<div class='alert alert-danger text-center'><b> Este e-mail já foi cadastrado, por favor informe outro! </b></div>");
			}else{
				$("#modal_form").modal('hide');
				$("#myModal").modal("show");
				$(".modal-title2").html("<h2 class='text-center'><b> Informação </b></h2>");
				$("#msgModal").html("<div class='alert alert-success text-center'><b> Erro ao cadastrar cliente! </b></div>");
			}
		});
	}

	function editCliente(id) {
		$("#form")[0].reset();
		$.ajax({
			url: "modulos/clientes/clientes.php?acao=getCliente&id=" + id,
			type: "POST",
			dataType: "JSON",
			success: function (retorno) {
				$('[name="id"]').val(retorno.id);
				$('[name="nome"]').val(retorno.nome);
				$('[name="cpf"]').val(retorno.cpf);
				$('[name="endereco"]').val(retorno.endereco);
				$('[name="email"]').val(retorno.email);
				$('[name="bairro"]').val(retorno.bairro);
				$('[name="cep"]').val(retorno.cep);
				$('[name="fone1"]').val(retorno.fone1);
				$('[name="fone2"]').val(retorno.fone2);
				$('[name="datanasc"]').val(retorno.datanasc);
				$('[name="cidade"]').val(retorno.cidade);
				$('[name="senha"]').val(retorno.senha);
				if (retorno.status == 'A') {
					$('[name="status"]').prop('checked', true);
				}
				if (retorno.maladireta == 'S') {
					$('[name="maladireta"]').prop('checked', true);
				}
				if (retorno.tipo == "C") {
					$('input:radio[id=tipo_c]').prop('checked', true);
				} else if (retorno.tipo == "F") {
					$('input:radio[id=tipo_f]').prop('checked', true);
				} else if (retorno.tipo == "G") {
					$('input:radio[id=tipo_g]').prop('checked', true);
				}
				if (retorno.sexo === 'F') {
					$('input:radio[name="sexo"][value="F"]').prop('checked',true);
				}else {
					$('input:radio[name="sexo"][value="M"]').prop('checked',true);
				}
				$("#modal_form").modal("show");
				$(".modal-title").html("<h2 class='text-center'><b> Alterar Cliente </b></h2>");
			},
			error: function (xhr, textStatus, error) {
				console.log("Erro ao processar requisição");
			}
		});
	}

	$('[name="cep"]').mask('00000-000');
	$('[name="cpf"]').mask('000.000.000-00');
	$('[name="fone1"]').mask('(00) 00000-0000');
	$('[name="fone2"]').mask('(00) 00000-0000');
	$('[name="datanasc"]').mask('00/00/0000');

	function delCliente(id) {
		if (confirm('Confirma exclusão?')) {
			$.ajax({
				url: "modulos/clientes/clientes.php?acao=del&id=" + id,
				type: "GET",
				dataType: "TEXT",
			}).done(function(retorno){
				if(retorno == "ok"){
					$("#myModal").modal("show");
					$(".modal-title2").html("<h2 class='text-center'><b> Informação </b></h2>");
					$("#msgModal").html("<div class='alert alert-success text-center'><b> Cliente excluído com sucesso! </b></div>");
					table.ajax.reload(null, false);
				}else{
					$("#myModal").modal("show");
					$(".modal-title2").html("<h2 class='text-center'><b> Informação </b></h2>");
					$("#msgModal").html("<div class='alert alert-danger text-center'><b> Cliente não pode ser excluído,\n\
					pois existem dados atrelados a ele! </b> </div>");
				}
			});
		}
	}

	function load_cidade() {
		$.ajax({
			url: "modulos/clientes/clientes.php?acao=get_MenuCidades",
			type: "GET",
			dataType: "HTML",
			success: function (retorno) {
				$(retorno).appendTo('.cid_id');
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log('Erro ao processar Cidade!')
			}
		});
	}
});
