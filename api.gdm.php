<?php

//DRUPAL
chdir('../web/');
define('DRUPAL_ROOT', getcwd());
require_once "./includes/bootstrap.inc";
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

//OJO!
$GLOBALS['base_url'] = "http://205.251.133.194/~guerrade/web";


require_once('Card.php');

/*********** aux functions ***********/

/* Función de retorno de la API */
function jReturn($var){
	print json_encode($var);
}

function cmpTax($a,$b){
	return ($a['id'] < $b['id']) ? -1 : 1;
}

function getTaxonomy($name){
	$vid = _get_vid($name);
	$taxs = taxonomy_get_tree($vid);
	$items = array();
	foreach($taxs as $t)
		$items[$t->tid] = array('id' => intval($t->tid), 'name' => $t->name);
	uasort($items, 'cmpTax');
	return $items;
}

function _get_vid($vocabulary_name) {
	// replace dashes with spaces
	$vocabulary_name = str_replace('-', ' ', $vocabulary_name);
	
	// find the vid
	$result = db_select("taxonomy_vocabulary","v")
			->fields('v', array('vid'))
			->condition('machine_name',$vocabulary_name,'=')
			->execute()
			->fetchObject();

	if($result->vid){
		return $result->vid;
	}else{
		return FALSE;
	}
}

/*********** end aux functions ***********/

function hello(){
	$ar = array(
		'version' => '0.1',
		'name' => 'API GDM',
	);
	
	jReturn($ar);
}


function init(){
	
	$array = array();
	
	$array['mythologies'] = getTaxonomy('mitologias');
	$array['expansions'] = getTaxonomy('expansiones');
	$array['keywords'] = getTaxonomy('claves');	
	
	jReturn($array);
}

function getCards($order = 'title', $direction = 'ASC', $limit = 10, $page = 0, $filters = array()){
	
	if($order=='') $order = "title";
	
	$cards = array();
	
	$query = db_select('node','n');
	
	$query->join('field_data_field_id','fid','n.nid=fid.entity_id');
	$query->join('field_data_field_tipo','t','n.nid=t.entity_id');
	$query->join('field_data_field_expansion','ex','n.nid=ex.entity_id');
	$query->join('field_data_field_mitologia','mi','n.nid=mi.entity_id');		
	
	$query->join('field_data_field_ilustrador','il','n.nid=il.entity_id');
	$query->join('node','nil','il.field_ilustrador_nid=nil.nid');
    
    $query->join('field_data_field_imagen','img','n.nid=img.entity_id');
	//$query->join('file_managed','fi','img.field_imagen_fid=fi.fid');
	
	$query->leftjoin('field_data_field_coste','c','n.nid=c.entity_id');
	$query->leftjoin('field_data_field_fuerza','f','n.nid=f.entity_id');
	$query->leftjoin('field_data_field_poder_max','pm','n.nid=pm.entity_id');
	$query->leftjoin('field_data_field_claves','kw','n.nid=kw.entity_id');
	$query->leftjoin('field_data_field_texto_influencias','ti','n.nid=ti.entity_id');
	$query->leftjoin('field_data_field_texto_enfrentamiento','te','n.nid=te.entity_id');
	$query->leftjoin('field_data_field_texto_flavor','tf','n.nid=tf.entity_id');
	$query->leftjoin('field_data_field_unico','u','n.nid=u.entity_id');
	$query->leftjoin('field_data_field_promo','p','n.nid=p.entity_id');
	
	
    
	$query->fields('n', array('nid','title'))
		->fields('fid',array('field_id_value'))
		->fields('t',array('field_tipo_value'))
		->fields('ex',array('field_expansion_tid'))
		->fields('mi',array('field_mitologia_tid'))
		->fields('nil',array('nid','title'))
		->fields('c',array('field_coste_value'))
		->fields('f',array('field_fuerza_value'))
		->fields('pm',array('field_poder_max_value'))
		->fields('ti',array('field_texto_influencias_value'))
		->fields('te',array('field_texto_enfrentamiento_value'))
		->fields('tf',array('field_texto_flavor_value'))
		->fields('u',array('field_unico_value'))
		->fields('p',array('field_promo_value'));
	
	$query->condition('n.type','carta')
		->condition('n.status',1);
	
	$query->orderBy($order, $direction);
	$query->groupBy('nid');
	$query->range($page,$limit);
	
	$query->addExpression('GROUP_CONCAT(DISTINCT img.field_imagen_fid ORDER BY img.field_imagen_fid ASC )', 'images');
	$query->addExpression('GROUP_CONCAT(DISTINCT kw.field_claves_tid ORDER BY kw.field_claves_tid ASC)', 'keywords');
	
	$results = $query->execute();
	
	while($res = $results->fetchObject())
		$cards[] = new Card($res);
	
	jReturn($cards);
	
}

