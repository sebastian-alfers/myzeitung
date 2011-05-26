<?php

class DATABASE_CONFIG
{
	//initalize variable as null
	var $default=null;

	//set up connection details to use in Live production server
	var $test =
		array(
            'driver' => 'mysql',
            'persistent' => false,
            'host' => 'mysql5.on-line-solutions.de',
            'port' => '',
            'login' => 'db214998_8',
            'password' => 'databa$e',
            'database' => 'db214998_8',
		);

	// and details to use on your local machine for testing and development
	var $dev = array(
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
		//check to see if server name is set (thanks Frank)
		if(Configure::read('Hosting.environment.local')){
            $this->default = $this->dev;
        }

        if(Configure::read('Hosting.environment.dev')){
            $this->default = $this->test;
		}

	}
}