var acao = "";
var table;

$(document).ready(function() {


	table = $('#cidades').DataTable({
		"ajax": 'modulos/cidades/cidades.php',
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

	$(document).on('click', '#addCidade', function(){
		abreModal();
	});

	function abreModal(){
		acao = "add";
		$("#form")[0].reset();
		$("#modal_form").modal('show');
		$(".modal-title").html("<h2 class='text-center'><b>Adicionar Cidade</b></h2>");
	}

	$(document).on('submit', '#form', function(e){
		e.preventDefault();
		save();
	});

	$(document).on('click', 'button.btn', function(){
		var id = $(this).data('id');
		var action = $(this).data('action');
		console.log("id: " + id + " | acao: " + action);
		if (action == "edit"){
			acao = action;
			editCidade(id);
		}else if (action == "del"){
			acao = action;
			delCidade(id);
		}
	});

	function save(){
		var url = "modulos/cidades/cidades.php?acao="+acao;
		var form  = new FormData($("#form")[0]);
		$.ajax({
			url : url,
			type : "POST",
			data : form,
			dataType : "text",
			processData : false,
			contentType : false,
			success : function(data){
				$("#modal_form").modal('hide');
				table.ajax.reload(null, false);
				$("#myModal").modal("show");
				$(".modal-title2").html("<h2 class='text-center'><b>Informação</b></h2>");
				$("#msgModal").html("<div class='alert alert-success text-center'><b> A cidade foi cadastrada com sucesso! </b></div>");
			},
			error : function(xhr, testStatus, error){
				console.log("Erro ao processar solicitação");
			}
		});
	}

	function editCidade(id){

		$("#form")[0].reset();
		$.ajax({
			url : "modulos/cidades/cidades.php?acao=getCidade&id="+id,
			type : "POST",
			dataType : "JSON",
			success : function(retorno){
				$('[name="id"]').val(retorno.id);
				$('[name="nome"]').val(retorno.nome);
				$('[name="uf"]').val(retorno.uf);
				$("#modal_form").modal("show");
				$(".modal-title").html("<h2 class='text-center'><b> Alterar Cidade </b></h2>");
			},
			error : function (xhr, textStatus, error){
				console.log("Erro ao processar solicitação");
			}
		});
	}

	function delCidade(id){
		if (confirm('Confirma exclusão?')){
			$.ajax({
				url : "modulos/cidades/cidades.php?acao=del&id="+id,
				type : "GET",
				dataType : "TEXT",
			}).done(function(retorno){
				if(retorno == "ok"){
					$("#myModal").modal("show");
					$(".modal-title2").html("<h2 class='text-center'><b> Informação </b></h2>");
					$("#msgModal").html("<div class='alert alert-success text-center'> <b>A cidade foi excluída com sucesso! </b></div>");
					table.ajax.reload(null, false);
				}else{
					$("#myModal").modal("show");
					$(".modal-title2").html("<h2 class='text-center'><b> Informação </b></h2>");
					$("#msgModal").html("<div class='alert alert-danger text-center'> <b> A cidade não pode ser excluída,\n\
					pois existem dados atrelados a ela! </b> </div>");
				}
			});
		}
	}
});
