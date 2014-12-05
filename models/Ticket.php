<?php

class Ticket {
	// conexion to DB
	private $linkDB;
	private $stock;
	private static $stockTable = "stock";

	function __construct($link, $stock) {
		$this->linkDB = $link;
		$this->stock = $stock;
	}

	function save($jsonTicket) {
		$ticket = json_decode($jsonTicket);
		$hashes = $ticket->details->hash;
		
		foreach ($hashes as $hash) {
			$this->stock->saleItem($hash);
		}
		$details = json_encode($ticket->details);
		$details = mysqli_escape_string($this->linkDB->link, $details);
		$sql  = "INSERT INTO `tickets` (`id`, `user`, `date`, `nItems`, `total`, `details`) VALUES (NULL, '$ticket->user', CURRENT_TIMESTAMP, '$ticket->nItems', '$ticket->total', '$details')";
		$this->linkDB->makeQuery($sql);
	}




}

?>