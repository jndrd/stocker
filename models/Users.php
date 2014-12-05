<?php

class User {
	private $id;
	private $first_name;
	private $range;
	private $last_name;
	private $fb;
	private $name;
	private $username;
	private $picture;
	private $cover;
	private $lastStore;
	private $rank;
	private $db;
	public static $tableName = "users";

	function __construct($db) {
		$this->db = $db;
	}

	public static function makeUser($fbURL ) {
		$graphUrl = "http://graph.facebook.com/";
		$data = explode("/", $fbURL);
		// we suppose that the 4th row contains the username
		$username = explode("?",$data[3]);
		$username = $username[0];
		
		$r = new HttpRequest($graphUrl.$username, HttpRequest::METH_GET);
		try {
		    $r->send();
		    if ($r->getResponseCode() == 200) {
		        echo $r->getResponseBody();
		    }
		} catch (HttpException $ex) {
		    echo $ex;
		}

	}

	public function exist($username) {
		$sql = "SELECT * FROM `users` WHERE `username` LIKE '$username'";
		if($this->db != null) {
			$result = $this->db->makeQuery($sql);
			if($result->num_rows > 0) {
				return true;
			} else {
				return false;
			}
		}
	}

	public function addUser($id,$first_name,$range,$last_name,$fb,$name,$username,$picture,$cover,$lastStore,$rank,$secret) {
		$id= $this->db->clean($id);
		$first_name= $this->db->clean($first_name);
		$range= $this->db->clean($range);
		$last_name= $this->db->clean($last_name);
		$fb= $this->db->clean($fb);
		$name= $this->db->clean($name);
		$username= $this->db->clean($username);
		$picture= $this->db->clean($picture);
		$cover= $this->db->clean($cover);
		$lastStore= $this->db->clean($lastStore);
		$rank= $this->db->clean($rank);
		$secret = sha1($secret);

		$sql = "INSERT INTO `stock`.`users` (`id`, `first_name`, `range_`, `last_name`, `fb`, `name`, `username`, `picture`, `cover`, `storeName`, `rank`,`secret` ) VALUES ('$id', '$first_name', '$range', '$last_name', '$fb', '$name', '$username', '$picture', '$cover', '$lastStore', '$rank','$secret')";
		if($this->db != null) {
			if(!$this->exist($username)) {
				$result = $this->db->makeQuery($sql);
				if($result) {
					echo json_encode(array("status"=>"ok"));
				} else {
					echo json_encode(array("status"=>"fail"));
				}
			} else {
					echo json_encode(array("status"=>"exist"));
			}
		}
	}

	public function login($username, $secret) {
		$secret = sha1($secret);
		$sql = "SELECT * FROM `users` WHERE `username` LIKE '$username' AND `secret` LIKE '$secret'";
		$result = $this->db->makeQuery($sql);

		if ($result->num_rows == 1) {
			return json_encode(array("login" => "ok", "data"=>$result->fetch_row()));
		} else {
			return json_encode(array("login" => "fail", "data"=>null));
		}
	}

	public function isAdmin($username) {
		$secret = sha1($secret);
		$sql = "SELECT * FROM `users` WHERE `username` LIKE '$username'";
		$result = $this->db->makeQuery($sql);

		if ($result->num_rows == 1) {
			$row = $result->fetch_row();
			return $row['range'] == 1;
		} else {
			return false;
		}
	}



}

?>