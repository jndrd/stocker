<?php
ob_start();
	session_start();

	$uri = "/stock/login";
		include 'lib/linkDataBase.php'; 
		include 'models/Stock.php';
		include 'models/Users.php';
		include 'models/Ticket.php';
		include 'models/Report.php';
		$db = new Connection();
		$stock = new Stock($db);
		$ticket =  new Ticket($db, $stock);
		$report = new Report($db);

		$user = new User($db);
		$search = (isset($_GET["search"])) ? $_GET["search"] : null;
		$code = (isset($_POST["code"])) ? $_POST["code"] : null;
		$urrss = (isset($_POST["username"])) ? $_POST["username"] : null;
		$login = (isset($_POST["login"])) ? $_POST["login"] : null;
		$logout = (isset($_GET["userInfo"])) ? $_GET["userInfo"] : null;
		$exit = (isset($_GET["exit"])) ? $_GET["exit"] : null;
		$details = (isset($_POST["details"])) ? $_POST["details"] : null;
		$wantReport = (isset($_POST["report"])) ? $_POST["report"] : null;
		$rank = (isset($_GET["rank"])) ? $_GET["rank"] : null;


		if($search) {
			$limit = (isset($_GET["limit"])) ? $_GET["limit"] : 10;
			$limit = ($limit > 0) ? $limit : 10;
			echo $stock->search($search,$limit);
		} else if($code) {
			echo $stock->details($code);
		} else if ($urrss) {
			$user->addUser($_POST["id"],$_POST["first_name"],$_POST["range"],$_POST["last_name"],$_POST["fb"],$_POST["name"],$_POST["username"],$_POST["photo"],$_POST["cover"],"zavala",$_POST["rank"],$_POST["secret"]);
		} else if($login) {
			echo User::login($_POST["login"],$_POST["secret"]);
		} else if ($logout) {
			echo json_encode($_SESSION["user"]);
		} else if($details) {
			echo $ticket->save(json_encode($_POST));
		} else if($wantReport) {
			echo $report->view_today_resume();
		} else if ($rank) {
			echo $report->rankUser($_SESSION["user"][6]);
		} else if($exit) {
			unset($_SESSION["user"]);
			session_destroy();
		}
	
	if(!isset($_SESSION["user"])) {
		header("Location: $uri");
	} else {
		header('Content-type: application/json');
	}
	ob_flush();
	exit;

?>