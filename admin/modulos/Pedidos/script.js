var acao = "";
var table;

$(document).ready(function () {

	table = $('#pedidos').DataTable({
		"ajax": 'modulos/pedidos/pedidos.php',
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

	$(document).on('submit', '#form', function (e) {
		e.preventDefault();
		save();
	});

	$(document).on('click', 'button.btn', function () {
		var id = $(this).data('id');
		var action = $(this).data('action');
		if (action == "edit") {
			acao = action;
			editPedido(id);
		} else if (action == "del") {
			acao = action;
			delPedido(id);
		}
	});

	function save() {
		var url = "modulos/pedidos/pedidos.php?acao=" + acao;
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
				$("#msgModal").html("<div class='alert alert-success text-center'><b> O pedido foi alterado com sucesso! </b></div>");
			},
			error: function (xhr, testStatus, error) {
				console.log("erro: " + textStatus);
				alert("Erro ao processar solicitação");
			}
		});
	}

	function editPedido(id) {

		$("#form")[0].reset();
		$.ajax({
			url: "modulos/pedidos/pedidos.php?acao=getPedido&id=" + id,
			type: "POST",
			dataType: "JSON",
			success: function (retorno) {
				$('[name="id"]').val(retorno.id);
				$('[name="dataenvio"]').val(retorno.dataenvio);
				if (retorno.status == "1") {
					$('input:radio[id=status_1]').prop('checked', true);
				} else if (retorno.status == "2") {
					$('input:radio[id=status_2]').prop('checked', true);
				} else if (retorno.status == "3") {
					$('input:radio[id=status_3]').prop('checked', true);
				}else if (retorno.status == "4")	{
					$('input:radio[id=status_4]').prop('checked', true);
				}else if (retorno.status == "5") {
					$('input:radio[id=status_5]').prop('checked', true);
				}
				$("#modal_form").modal("show");
				$(".modal-title").html("<h2 class='text-center'><b> Alterar Pedido </b></h2>");
			},
			error: function (xhr, textStatus, error) {
				console.log("Erro ao processar requisição");
			}
		});
	}
	$('[name="dataenvio"]').mask('00/00/0000');

	function delPedido(id) {
		if (confirm('Confirma exclusão?')) {
			$.ajax({
				url: "modulos/pedidos/pedidos.php?acao=del&id=" + id,
				type: "GET",
				dataType: "TEXT",
			}).done(function(retorno){
				if(retorno == "ok"){
					$("#myModal").modal("show");
					$(".modal-title2").html("<h2 class='text-center'><b> Informação </b></h2>");
					$("#msgModal").html("<div class='alert alert-success text-center'><b> O pedido foi excluído com sucesso! </b></div>");
					table.ajax.reload(null, false);
				}else{
					$("#myModal").modal("show");
					$(".modal-title2").html("<h2 class='text-center'><b> Informação </b></h2>");
					$("#msgModal").html("<div class='alert alert-danger text-center'><b> O pedido não pode ser excluído,\n\
					pois existem dados atrelados a ele! </b> </div>");
				}
			});
		}
	}
});
