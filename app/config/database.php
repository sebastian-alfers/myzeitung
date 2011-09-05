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
            'host' => 'db01.cr12ebyckgtr.eu-west-1.rds.amazonaws.com',
            'port' => '3306',
            'login' => 'mz_rds',
            'password' => 'Ymqodfi3',
            'database' => 'mzdb',
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
