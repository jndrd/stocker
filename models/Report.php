<?php

class Report {
	// conexion to DB
	private $linkDB;
	private static $stockTable = "tickets";

	function __construct($link) {
		$this->linkDB = $link;
	}

	public function view_today_resume() {
		$resume = array();
		$resume["date"] = date("Y-m-d H:i:s");
		$resume["totalOfDay"] =(int) $this->getTotalDay();
		$resume["saledItems"] = (int) $this->getSaledItems();
		$resume["details"] = $this->getDetails();
		$resume["positions"] = $this->rankUsers();
		return json_encode($resume);
	}

	private function getTotalDay()  {
		$sql = "SELECT SUM(total) AS totalOfDay FROM tickets";
		$result = $this->linkDB->makeQuery($sql);
		$row = $result->fetch_row();
		return $row[0];
	}

	private function getSaledItems()  {
		$sql = "SELECT SUM(nItems) AS saledItems FROM tickets";
		$result = $this->linkDB->makeQuery($sql);
		$row = $result->fetch_row();
		return $row[0];
	}

	private function getDetails() {
		$results = array();
		$sql = "SELECT details FROM tickets WHERE date > CURDATE()";
		$result = $this->linkDB->makeQuery($sql);
		while($row = $result->fetch_row()) {
			$results[] = json_decode($row[0]);
		}
		return $results;
	}

	public function rankUser($username) {
		$sql = "SELECT SUM(total) AS total FROM `tickets` WHERE (`user` LIKE '$username' AND date > CURDATE())";
		$sql2 = "SELECT SUM(nItems) AS total FROM `tickets` WHERE (`user` LIKE 'ndrd4' AND date > CURDATE())";

		$result = $this->linkDB->makeQuery($sql);
		$row = $result->fetch_row();
		$totalSale = $row[0];

		$result = $this->linkDB->makeQuery($sql2);
		$row = $result->fetch_row();
		$saledItems = $row[0];

		$dUser = $totalSale/$saledItems;
		$dDay = $this->getTotalDay()/$this->getSaledItems();
		$rank = $dUser/$dDay * 100;
		return array("username"=>$username, "rank"=>$rank);

	}

	public function rankUsers() {
		$result = array();
		$sql = "SELECT username FROM `users` WHERE 1";
		$result = $this->linkDB->makeQuery($sql);
		while($row = $result->fetch_row()) {
			$results[] = $this->rankUser($row[0]);
		}
		return $results;

	}
}
?>
