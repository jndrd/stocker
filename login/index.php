<?php
	include '../lib/linkDataBase.php'; 
	include '../models/Users.php';
	ob_start();
	session_start();
	$uri = "/stock";

	if(isset($_SESSION["user"])) {
		header("Location: $uri");
	} else {
		if(isset($_POST["login"]) && strlen($_POST["login"]) > 1 && isset($_POST["secret"]) && strlen($_POST["secret"]) > 5) {
			$db = new Connection();
			$user = new User($db);
			$response = json_decode($user->login($_POST["login"],$_POST["secret"]));
		
			if ($response->login== "ok") {
				$_SESSION["user"] = $response->data;
				header("Location: $uri");
			}
		}else {
			$myFile = "log.html";
			$fh = fopen($myFile, 'r');
			$theData = fread($fh, filesize($myFile));
			fclose($fh);
			echo $theData;
		}
	}
	
	ob_flush();
	exit;
?>