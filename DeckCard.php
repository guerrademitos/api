<?php

require_once('api.gdm.php');

class DeckCard{
	
	public $deckId;
	public $cardId;
	public $quantity;
	
	function __construct($c){
		$this->deckId = intval($c->deckId);
        $this->cardId = intval($c->cardId);
        $this->quantity = intval($c->quantity);
	}	
}