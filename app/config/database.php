<?php

class DATABASE_CONFIG
{
  /*
	// default
	var $default = array(
		'driver' => 'mysql',
		'persistent' => false,
		'host' => '127.0.0.1',
		'login' => 'root',
		'password' => '',
		'database' => 'myzeitung',
		'prefix' => '',
	);
  */

	// local
	var $local = array(
            'driver' => 'mysql',
            'persistent' => false,
            'host' => '127.0.0.1',
            'port' => '',
            'login' => 'root',
            'password' => 'root',
            'database' => 'myzeitung',
	);




	// the construct function is called automatically, and chooses prod or dev. UPdate! works for baking now
	function __construct ()
	{
        $this->default = $this->local;

	}
}
