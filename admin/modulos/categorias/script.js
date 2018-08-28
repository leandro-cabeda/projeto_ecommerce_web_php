var acao = "";
var table;

$(document).ready(function () {

	table = $('#categorias').DataTable({
		"ajax": 'modulos/categorias/categorias.php',
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

	$(document).on('click', '#addCategoria', function () {
		abreModal();
	});

	function abreModal() {
		acao = "add";
		$("#form")[0].reset();
		$("#modal_form").modal('show');
		$(".modal-title").html("<h2 class='text-center'><b>Adicionar Categoria</b></h2>");
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
			editCategoria(id);
		} else if (action == "del") {
			acao = action;
			delCategoria(id);
		}
	});

	function save() {
		var url = "modulos/categorias/categorias.php?acao=" + acao;
		var form = new FormData($("#form")[0]);
		$.ajax({
			url: url,
			type: "POST",
			data: form,
			dataType: "text",
			processData: false,
			contentType: false,
			success: function (data) {
				$("#modal_form").modal('hide');
				table.ajax.reload(null, false);
				$("#myModal").modal("show");
				$(".modal-title2").html("<h2 class='text-center'><b>Informação</b></h2>");
				$("#msgModal").html("<div class='alert alert-success text-center'><b> A categoria foi cadastrada com sucesso! </b></div>");
			},
			error: function (xhr, testStatus, error) {
				console.log("Erro ao processar solicitação");
			}
		});
	}

	function editCategoria(id) {
		$("#form")[0].reset();
		$.ajax({
			url: "modulos/categorias/categorias.php?acao=getCategoria&id=" + id,
			type: "POST",
			dataType: "JSON",
			success: function (retorno) {
				$('[name="id"]').val(retorno.id);
				$('[name="descricao"]').val(retorno.descricao);
				$("#modal_form").modal("show");
				$(".modal-title").html("<h2 class='text-center'><b> Alterar Categoria </b></h2>");
			},
			error: function (xhr, textStatus, error) {
				alert("Erro ao processar requisição");
			}
		});
	}

	function delCategoria(id) {
		if (confirm('Confirma exclusão?')) {
			$.ajax({
				url: "modulos/categorias/categorias.php?acao=del&id=" + id,
				type: "GET",
				dataType: "TEXT",
			}).done(function(retorno){
				if(retorno == "ok"){
					$("#myModal").modal("show");
					$(".modal-title2").html("<h2 class='text-center'><b> Informação </b></h2>");
					$("#msgModal").html("<div class='alert alert-success text-center'> <b>A categoria foi excluída com sucesso! </b></div>");
					table.ajax.reload(null, false);
				}else{
					$("#myModal").modal("show");
					$(".modal-title2").html("<h2 class='text-center'><b> Informação </b></h2>");
					$("#msgModal").html("<div class='alert alert-danger text-center'> <b> A categoria não pode ser excluída,\n\
					pois existem dados atrelados a ela! </b> </div>");
				}
			});
		}
	}
});
