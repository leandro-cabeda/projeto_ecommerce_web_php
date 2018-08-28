var acao ="";
var table;

$(document).ready(function() {
	table = $('#pedidos').DataTable({
		"ajax": '../publica/pedidos.php',
		"language": { "sEmptyTable": "Nenhum registro encontrado", "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros", "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
		"sInfoFiltered": "(Filtrados de _MAX_ registros)", "sInfoPostFix": "", "sInfoThousands": ".", "sLengthMenu": "_MENU_ Resultados por página",
		"sLoadingRecords": "Carregando...", "sProcessing": "Processando...", "sZeroRecords": "Nenhum registro encontrado", "sSearch": "Pesquisar",
		"oPaginate": { "sNext": "Próximo", "sPrevious": "Anterior", "sFirst": "Primeiro", "sLast": "Último" },
		"oAria": { "sSortAscending": ": Ordenar colunas de forma ascendente", "sSortDescending": ": Ordenar colunas de forma descendente" }
	},
	"bInfo" : false
});

$(document).on('click', '.btn-view', function(){
	var id = $(this).data('id');
	$("#modal_view").modal('show');
	$(".modal-title").text("Itens do Pedido "+id);
	$("#itens_pedidos").DataTable({
		"ajax": 'pedidos.php?acao=getItensPedido&id='+id,
		destroy: true,
		searching: false,
		paging: false,
		"bInfo" : false
	});
});

});
