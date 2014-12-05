<?php

class Stock {

	// conexion to DB
	private $linkDB;
	private static $stockTable = "stock";

	function __construct($link) {
		$this->linkDB = $link;
	}

	public  function addItem($item) {
		$sql = "INSERT INTO " . Stock::$stockTable . " values ('$item->id' , '$item->hash', '$item->code', '$item->description', '$item->sale' , '$item->cost' , '$item->stock' , '$item->rank', '$item->lastEdit', '$item->category' )";
		$this->linkDB->makeQuery($sql);
	}

	public function updateItem($code, $item) {

	}

	public function search($description, $limit) {
		$results = array();
		$description = mysqli_escape_string($this->linkDB->link, $description);
		$sql = "SELECT `hash`, `code`, `description`, `sale`, `rank`, `category`, `stock`,`lastEdit` FROM `stock` WHERE `description` LIKE '%$description%' ORDER BY `rank` DESC LIMIT $limit";
		$result = $this->linkDB->makeQuery($sql);
		while($fila = $result->fetch_row()) {
			$results[] = $fila;
		}
		return json_encode(array("data"=>$results));
    }

	
	public function details($code) {
		$sql = "SELECT `hash`, `code`, `description`, `sale`, `rank`, `category` FROM `stock` WHERE `code` LIKE '$code'";
		$result = $this->linkDB->makeQuery($sql);
		if($result) {
			if($result->num_rows < 1) {
				$r = 0;
			} else {
				$r = 1;
			}
			return json_encode(array("status" => $r, 'item' => $result->fetch_row()));
		}


	}

	public function hashDetails($hash) {
		$sql = "SELECT `hash`, `code`, `description`, `sale`, `rank`, `category` FROM `stock` WHERE `hash` LIKE '$hash'";
		$result = $this->linkDB->makeQuery($sql);
		if($result) {
			if($result->num_rows < 1) {
				$r = 0;
			} else {
				$r = 1;
			}
			return json_encode(array("status" => $r, 'item' => $result->fetch_row()));
		}

	}

	public function saleItem($hash) {
		$sql = "UPDATE `stock` SET `stock`=0, WHERE (`hash` LIKE '$hash' AND `lastEdit` < CURDATE()";
		$this->linkDB->makeQuery($sql);
		$sql = "UPDATE `stock` SET `stock`=`stock` + 1, `rank`=`rank` + 0.01 WHERE `hash` LIKE '$hash'";
		$this->linkDB->makeQuery($sql);
	}

	
}

?>