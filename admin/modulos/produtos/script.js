var acao = "";
var table;

$(document).ready(function() {

	load_categoria();

	table = $('#produtos').DataTable({
		"ajax": 'modulos/produtos/produtos.php',
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

	$(document).on('click', '#addProduto', function(){
		abreModal();
	});

	function abreModal(){
		acao = "add";
		$("#form")[0].reset();
		$("#modal_form").modal('show');
		$(".modal-title").html("<h2 class='text-center'><b>Adicionar Produto</b></h2>");
		$( "img" ).remove( ".imgprod" );
	}

	$(document).on('submit', '#form', function(e){
		e.preventDefault();
		save();
	});

	$(document).on('click', 'button.btn', function(){
		var id = $(this).data('id');
		var action = $(this).data('action');
		if (action == "edit"){
			acao = action;
			editProduto(id);
		}else if (action == "del"){
			acao = action;
			delProduto(id);
		}
	});

	$(document).on('change', '#file', function(){
		$("#figura").attr("src", $('#file').val());
		// if (typeof (FileReader) != "undefined") {
			var reader = new FileReader();
			reader.onload = function (e) {
				$('#form img').remove();
				$('#imagem').after('<img class="imgprod" style="height: 200px; width: 200px;" src="'+e.target.result+'"/>');
			}
			reader.readAsDataURL($(this)[0].files[0]);
		// }
	});

	function save(){
		var url = "modulos/produtos/produtos.php?acao="+acao;
		var form  = new FormData($("#form")[0]);
		$.ajax({
			url : url,
			type : "POST",
			data : form,
			dataType : "text",
			processData : false,
			contentType : false,
		}).done(function(retorno){
			if(retorno == "ok"){
				$("#modal_form").modal('hide');
				table.ajax.reload(null, false);
				$("#myModal").modal("show");
				$(".modal-title2").html("<h2 class='text-center'><b> Informação </b></h2>");
				if (acao == "edit") {
					$("#msgModal").html("<div class='alert alert-success text-center'><b> Produto foi alterado com sucesso! </b></div>");
					setTimeout(function(){ window.location.reload(); }, 100);
				}else {
					$("#msgModal").html("<div class='alert alert-success text-center'><b> Produto foi cadastrado com sucesso! </b></div>");
					setTimeout(function(){ window.location.reload(); }, 100);
				}

			}else{
				$("#modal_form").modal('hide');
				$("#myModal").modal("show");
				$(".modal-title2").html("<h2 class='text-center'><b> Informação </b></h2>");
				$("#msgModal").html("<div class='alert alert-success text-center'><b> Erro ao cadastrar Produto! </b></div>");
				console.log(retorno);
			}
		});
	}

	function editProduto(id){

		$("#form")[0].reset();
		$.ajax({
			url : "modulos/produtos/produtos.php?acao=getProduto&id="+id,
			type : "POST",
			dataType : "JSON",
			success : function(retorno){
				$('[name="id"]').val(retorno.id);
				$('[name="descricao"]').val(retorno.descricao);
				$('[name="valor"]').val(retorno.valor);
				$('[name="detalhes"]').val(retorno.detalhes);
				$('[name="estoque"]').val(retorno.estoque);
				$('[name="peso"]').val(retorno.peso);
				$('[name="promocao"]').val(retorno.promocao);
				$('[name="categoria"]').val(retorno.categoria);
				$('[name="dimensao"]').val(retorno.dimensao);
				$( "img" ).remove( ".imgprod" );
				var imagem = retorno.imagem;
				if (imagem != ""){
					$('#imagem').after('<img class="imgprod" style="width: 200px; height: 200px"  src="'+imagem+'"/>');
				}
				$("#modal_form").modal("show");
				$(".modal-title").html("<h2 class='text-center'><b> Alterar Produto </b></h2>");
			},
			error : function (xhr, textStatus, error){
				console.log("Erro ao processar requisição");
			}
		});
	}

	$('[name="valor"]').mask("###.##0,00",{reverse: true});
	$('[name="promocao"]').mask("###.##0,00",{reverse: true});
	$('[name="dimensao"]').mask('00x00x00');

	function delProduto(id){
		if (confirm('Confirma exclusão?')){
			$.ajax({
				url : "modulos/produtos/produtos.php?acao=del&id="+id,
				type : "GET",
				dataType : "TEXT",
			}).done(function(retorno){
				if(retorno == "ok"){
					$("#myModal").modal("show");
					$(".modal-title2").html("<h2 class='text-center'><b> Informação </b></h2>");
					$("#msgModal").html("<div class='alert alert-success text-center'><b> O produto foi excluído com sucesso! </b></div>");
					table.ajax.reload(null, false);
				}else{
					$("#myModal").modal("show");
					$(".modal-title2").html("<h2 class='text-center'><b> Informação </b></h2>");
					$("#msgModal").html("<div class='alert alert-danger text-center'><b> O produto não pode ser excluído,\n\
					pois existem dados atrelados a ele! </b></div>");
				}
			});
		}
	}

	function load_categoria(){
		$.ajax({
			url: "modulos/produtos/produtos.php?acao=get_MenuCategorias",
			type: "GET",
			dataType: "HTML",
			success: function(retorno){
				$(retorno).appendTo('.cat_id');
			},
			error: function(jqXHR, textStatus, errorThrown){
				console.log('Erro ao processar Categoria!')
			}
		});
	}
});
