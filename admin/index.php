<?php
session_start();
if(isset($_SESSION['user_session'])!=""){
	header("Location: home.php");
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Login</title>
	<link href="../css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="style.css" rel="stylesheet" type="text/css" media="screen">
</head>

<body>
	<div class="signin-form">
		<div class="container">
			<form class="form-signin" method="post" id="login-form">
				<h2 class="form-signin-heading">Login</h2><hr>
				<div id="error"></div>
				<div class="form-group">
					<input type="text" class="form-control"
					required placeholder="Preencha o usuário" name="user"
					id="user" />
					<span id="check-e"></span>
				</div>
				<div class="form-group">
					<input type="password" class="form-control" required
					placeholder="Preencha a senha" name="password" id="password" />
				</div>
				<hr>
				<div class="form-group">
					<button type="submit" class="btn btn-primary" name="btn-login"	id="btn-login">
					<span id="btn-icon" class="glyphicon glyphicon-log-in"></span>
					<span id="btn-text">&nbsp; Login</span>
				</button>
			</div>
		</form>
	</div>
</div>

<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="script.js"></script>

</body>
</html>
