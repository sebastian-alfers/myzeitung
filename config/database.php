<?php
class DATABASE_CONFIG {

	var $default = array(
		'driver' => 'mysql',
		'persistent' => false,
		'host' => '127.0.0.1',
		'port' => '',
		'login' => 'root',
		'password' => 'root',
		'database' => 'myzeitung',
	);
	
	var $test = array(
		'driver' => 'mysql',
		'persistent' => false,
		'host' => '127.0.0.1',
		'port' => '',
		'login' => 'root',
		'password' => 'root',
		'database' => 'myzeitung',
		'prefix' => ''
	);	
}
?>