<?php
	ini_set('display_errors', '0');
	ob_start();
	session_start();

	function isthere($path) {
	    return ( file_exists($path) || file_exists($_SERVER['DOCUMENT_ROOT']."path") );
	}

	$file = isset($_GET['url']) ? $_GET['url'] : null; 
	$user = isset($_SESSION["user"])? $_SESSION["user"] : null;

	if(!$file) {
		$myFile = "views/blondie.stock";

	} else if($user) {
		$myFile = "views/". $file.".stock";

	}

	$html = null;
	$side = null;

	if(!isthere($myFile)) {
		$myFile = "views/404.stock";
	} 
		$fh = fopen($myFile, 'r');
		$html  = fread($fh, filesize($myFile));
		fclose($fh);
	

	if(!isthere($myFile."side")) {
		$myFile = "views/404.stock";
	} 
		$fh = fopen($myFile."side", 'r');
		$side  = fread($fh, filesize($myFile.".side"));
		fclose($fh);
	

	
	
	

	$side = htmlspecialchars($side,ENT_QUOTES);
	$html = htmlspecialchars($html, ENT_QUOTES);
	header('Content-type: application/json');
	echo json_encode(array("title"=>$file, "#content"=>$html,"aside"=>$side));

	ob_flush();
?>