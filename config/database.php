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
		if(isset($_SERVER['SERVER_NAME'])){
			switch($_SERVER['SERVER_NAME']){
				case 'myzeitung.loc':
					$this->default = $this->dev;
					break;
				case 'www.on-line-solutions.de':
					$this->default = $this->test;
					break;
			}
		}
	    else // we are likely baking, use our local db
	    {
	        $this->default = $this->dev;
	    }
	}
}