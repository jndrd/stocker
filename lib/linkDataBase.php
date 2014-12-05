<?php

class Connection {
	private $host;
    public static $dataBaseName;  
    private $user;  
    private $pswd;  
    public $link;
    

    function __construct() {
    	$this->host = "localhost";
    	$this->dataBaseName = "stock";
    	$this->user = "root";
    	$this->pswd = "";
    	$this->link = mysqli_connect($this->host, $this->user, $this->pswd, $this->dataBaseName);
    }

    function makeQuery($sql) {
        return mysqli_query($this->link, $sql);
    }

    function clean($str) {
        return mysqli_escape_string($this->link, $str);
    }

  
}
?>

