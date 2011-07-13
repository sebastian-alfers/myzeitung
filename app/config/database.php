<?php

class DATABASE_CONFIG
{

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

	// local
	var $local = array(
            'driver' => 'mysql',
            'persistent' => false,
            'host' => '127.0.0.1',
            'port' => '3306',
            'login' => 'root',
            'password' => 'root',
            'database' => 'myzeitung',
	);

	//currently on my domaingo server
	var $dev =
		array(
            'driver' => 'mysql',
            'persistent' => false,
            'host' => 'mysql5.on-line-solutions.de',
            'port' => '',
            'login' => 'db214998_8',
            'password' => 'databa$e',
            'database' => 'db214998_8',
		);

    //amazon
	var $live = array(
            'driver' => 'mysql',
            'persistent' => false,
            'host' => 'localhost',
            'port' => '',
            'login' => 'root',
            'password' => '',
            'database' => 'myzeitung',
	);


	// the construct function is called automatically, and chooses prod or dev. UPdate! works for baking now
	function __construct ()
	{
		//check to see if server name is set (thanks Frank)
		if(Configure::read('Hosting.environment.local')){
            $this->default = $this->local;
        }

        if(Configure::read('Hosting.environment.dev')){
            $this->default = $this->dev;
		}

        if(Configure::read('Hosting.environment.live')){
            $this->default = $this->live;
		}

	}
}
