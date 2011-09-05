<?php

App::import('Lib', 'PhpSsh', array('file' => 'Ssh/Client.php'));

class AwsController extends AppController {

    const SES = 'admin_ses_';
    const EC2 = 'admin_ec2_';

	var $name = 'Aws';



    var $components = array('Aws');

    var $uses = array();

    /**
     * wrapper for ses methods
     *
     * @return void
     */
    function admin_ses($method){

        $sshClient = new SshClient();

        $sshClient->connect();
        //$sshClient->exec('ls -l /');
        die();


        $view = $method;
        $data = array();

        switch($method){
            case 'quota':
                $data = $this->Aws->ses_get_send_quota();
                break;
            case 'adresses':
                $data = $this->Aws->list_verified_email_addresses();
                break;
        }

        $this->set('data', $data);
        $this->render(self::SES.$view);
    }


    /**
     * wrapper for ses methods
     *
     * @return void
     */
    function admin_ec2($method){
        $view = $method;
        $data = array();

        switch($method){
            case 'manage':
                $data = $this->Aws->ec2_describe_images();
                break;
        }

        $this->set('data', $data);
        $this->render(self::EC2.$view);
    }








	public function beforeFilter(){
		parent::beforeFilter();
	}

	function admin_describeImages() {


        $instances = $this->Aws->describeInstances();

        $this->set('instances', $instances);

        //$instances = $response->body->instancesSet;



	}


}
