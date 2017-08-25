<?php
	//clear cookie 
	setcookie("ACCOUNT", "");
	setcookie("PASSED", "");
	
	//導向登入頁
	header("location:log_in.html");
	exit();
?>