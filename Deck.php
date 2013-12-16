<?php

require_once('api.gdm.php');

class Deck{
	
	public $id;
	public $basedIn;
	public $creator;
	public $description;
	public $deckCards;
	
	function __construct($c){
		$this->id = intval($c->id);
        $this->basedIn = intval($c->basedIn);
        $this->creator = $c->creator;
        $this->description = $c->description;
        $this->deckCards = array();
	}	
}