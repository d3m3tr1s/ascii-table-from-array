<?php

require_once 'Aprint.php';

$array = array(
	array(
		'Name' => 'Trixie',
		'Color' => 'Green',
		'Element' => 'Earth',
		'Likes' => 'Flowers'
	),
	array(
		'Name' => 'Tinkerbell',
		'Element' => 'Air',
		'Likes' => 'Singning',
		'Color' => 'Blue'
	), 
	array(
		'Element' => 'Water',
		'Likes' => 'Dancing',
		'Name' => 'Blum',
		'Color' => 'Pink'
	),
);


if(php_sapi_name() != 'cli' or !empty($_SERVER['REMOTE_ADDR'])) {
	echo '<pre>';
}	

$obj = new Aprint($array);
echo  $obj->ascii();
echo "\r\n";

?>
