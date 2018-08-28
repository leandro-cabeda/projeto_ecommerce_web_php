<?php
	session_start();
	unset($_SESSION['usu_codigo']);
	unset($_SESSION['id']);
	if (session_destroy()) {
		header("Location: index.php");
	}
?>
