$('document').ready(function(){
	$("#login-form").on("submit", function(e){
		e.preventDefault();
		var dados = $("#login-form").serialize();
		$.ajax({
			type: "POST",
			url: "login_process.php",
			data: dados,
			beforeSend: function(){
				$("#error").fadeOut();
				$("#btn-icon").attr("class","glyphicon glyphicon-transfer");
				$("#btn-text").html("&nbsp; Carregando...");
			}
		}).done(function(retorno){
			if(retorno == "OK"){
				$("#btn-icon").attr("class","");
				$("#btn-text").html("Redirecionando...");
				setTimeout('window.location.href="home.php"',1000);
			}else{
				$("#error").fadeIn(1000,function(){
					$("#error").html('<div class="alert alert-danger" <span class="glyphicon glyphicon-info-sign">\
					</span> ' + retorno + '</div>');
					$("#btn-icon").attr("class", "glyphicon glyphicon-log-in");
					$("#btn-text").html("&nbsp Login");
				});
			}
		});
	});
});
