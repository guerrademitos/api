<?php

require_once('api.gdm.php');

class Card{
	
	public $id;
	public $code;
	public $name;
	public $expansion;
	public $mythology;
	public $type;
	public $artist;
	public $image;
	public $cost;
	public $strength;
	public $maxpower;
	public $keywords;
	public $influences_text;
	public $confrontation_text;
	public $flavor_text;
	public $unique;
	public $promo;
	
	function __construct($c){
		//Conversión directa del Drupal
		$this->id = intval($c->nid);
		$this->code = $c->field_id_value;
		$this->name = $c->title;
		$this->type = $c->field_tipo_value;
		$this->artist = array('id' => intval($c->nil_nid), 'name' => $c->nil_title);
		//$this->image = $c->images;//file_create_url($c->uri);
		$this->cost = intval($c->field_coste_value);
		$this->strength = intval($c->field_fuerza_value);
		$this->maxpower = intval($c->field_poder_max_value);
		$this->influences_text = $c->field_texto_influencias_value;
		$this->confrontation_text = $c->field_texto_enfrentamiento_value;
		$this->flavor_text = $c->field_texto_flavor_value;
		$this->unique = intval($c->field_unico_value) == 1 ? true : false;
		$this->promo = intval($c->field_promo_value) == 1 ? true : false;
		
		//Process images
		$fids = explode(',', $c->images);
		foreach($fids as $fid)
			$this->image[] = file_create_url(file_load($fid)->uri);
		
		//Process taxonomies
		$kw = getTaxonomy('claves');
		$ids = explode(',', $c->keywords);
		foreach($ids as $id)
			$this->keywords[] = $kw[$id];
		
		$ex = getTaxonomy('expansiones');
		$this->expansion = $ex[$c->field_expansion_tid];
		
		$my = getTaxonomy('mitologias');
		$this->mythology = $my[$c->field_mitologia_tid];
		
		
		
	}	
}