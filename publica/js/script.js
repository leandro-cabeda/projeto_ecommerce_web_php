$('document').ready(function() {

	load_cidade();

	function load_cidade(){
		$.ajax({
			url: "clientes.php?acao=getmenucidade",
			type: "GET",
			dataType: "HTML",
			success : function(retorno){
				$(retorno).appendTo('.cid_id');
			},
			error : function(jqXHR, textStatus, errorThrown){
				alert('Erro ao processar cidade!!');
			}
		});
	}

});
