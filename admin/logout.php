<?php

	$trackPage = "logout";
	
	require '../inc/config.php'; 

	session_destroy();

	ob_start();

	header('Location: index.php');
	exit;
?>