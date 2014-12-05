<?php

class Item {
	public $id;
	public $hash;
	public $code;
	public $description;
	public $sale;
	public $cost;
	public $rank;
	public $lastEdit;
	public $category;

	/*
	* create and return a new item object, like a constructor
	*/
	public static function parseItem($id, $hash, $code, $description, $sale, $cost, $stock, $rank, $lastEdit, $category) {
		$item = new Item();
		$item->id = $id;
		$item->hash = $hash;
		$item->code = $code;
		$item->description = $description;
		$item->sale = $sale;
		$item->cost = $cost;
		$item->stock = $stock;
		$item->rank = $rank;
		$item->lastEdit = $lastEdit;
		if ($category != 0) {
			$item->category = $category;
		} else {
			$item->category = $item->categoryze($description);
		}
		
		return $item;
	}	

	public function toJSON() {
		$array =  (array) $this;
		return json_encode($array);
	}

	/* 
	* categoryze an item by its description
	*/
	private function categoryze($description) {
		$category = 0;
		$words = explode(" ", $description);
		foreach ($words as $word) {
			$category =  $this->wordToCategory($word);
			if ($category != 0) {
				break;
			}
		}
		return $category;
	}

	/*
	* return an integer to classify 
	*/
	private function  wordToCategory($word) {
		$cat = 0;
		$cat4 = array("pepsi","valle","mineral","agua");
		$cat3 = array("sabritas","barcel","bimbo","cacahuates","galletas","marinela","lara","gamesa");
		$cat2 = array("coca");

		if (in_array($word, $cat4)) {
			$cat = 4;
		} else if (in_array($word, $cat3)) {
			$cat = 3;
		} else if (in_array($word, $cat2)) {
			$cat = 2;
		}

		return $cat;

	}

	

}

?>