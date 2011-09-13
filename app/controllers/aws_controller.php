<?php

App::import('Lib', 'PhpSsh', array('file' => 'Ssh/Client.php'));

class AwsController extends AppController {

    const SES = 'admin_ses_';
    const EC2 = 'admin_ec2_';
    const CF = 'admin_cf_';

	var $name = 'Aws';



    var $components = array('Aws');

    var $uses = array();

    /**
     * wrapper for ses methods
     *
     * @return void
     */
    function admin_ses($method){

        $view = $method;
        $data = array();

        switch($method){
            case 'quota':

                $data = $this->Aws->ses_get_send_statistics();
                //process data
                $data = $data->GetSendStatisticsResult->SendDataPoints;
                $grouped = array();
                $grouped['DeliveryAttempts'] = 0;
                $grouped['Rejects'] = 0;
                $grouped['Bounces'] = 0;
                $grouped['Complaints'] = 0;
                foreach($data->member as $statistic){
                    $grouped['DeliveryAttempts'] += $statistic->DeliveryAttempts;
                    $grouped['Rejects'] += $statistic->Rejects;
                    $grouped['Bounces'] += $statistic->Bounces;
                    $grouped['Complaints'] += $statistic->Complaints;
                }
                $data = $grouped;

                $this->set('quota', $this->Aws->ses_get_send_quota());

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

    /**
     * wrapper for cloudfront methods
     *
     * @return void
     */
    function admin_cf($method, $distribution_id = NULL){
        $view = $method;
        $data = array();

        switch($method){
            case 'distributions':
                $data = $this->Aws->cf_list_distributions();
                break;
            case 'invalidations':
                $distribution_id = $distribution_id;
                $data = $this->Aws->cf_list_invalidations($distribution_id);
                $this->set('distribution_id', $distribution_id);

                //process invlaidations
                $invalidations = array();
                $paths = array();
                if(isset($data->body->InvalidationSummary) && !empty($data->body->InvalidationSummary)){



                    if(isset($data->body->InvalidationSummary[0])){
                        foreach($data->body->InvalidationSummary as $element){

                            $details = $this->Aws->cf_get_invalidation($distribution_id, $element->Id);

                            if(count($details->body->InvalidationBatch->Path) > 1){
                                $path = '';
                                foreach($details->body->InvalidationBatch->Path as $p){
                                    $path .= "<br />". (String)$p;
                                    if(!isset($paths[(String)$p]) && substr((String)$p, 0, 4) == '/img'){
                                        $paths[(String)$p] = (String)$p;
                                    }
                                }

                            }
                            else{
                                $path = $details->body->InvalidationBatch->Path;
                                //add path for frontend;
                                if(!isset($paths[(String)$path]) && substr((String)$path, 0, 4) == '/img'){
                                    $paths[(String)$path] = (String)$path;
                                }
                            }

                            # only publish running invalidations to frontend
                            if((String)$element->Status == 'InProgress'){
                                $invalidations[] = array('id' => (String)$element->Id, 'status' => (String)$element->Status, 'created' => (String)$details->body->CreateTime, 'path' => $path);
                            }

                        }
                    }
                    else{
                        debug('implement me');
                        $invalidations[] = $data->body->InvalidationSummary;
                    }
                }
                $this->set('paths', $paths);
                $data = $invalidations;

                break;
            case 'createinvalidation':
                debug($this->data);
                $paths = array();
                if(isset($this->data['Path']['Paths']) && !empty($this->data['Path']['Paths'])){
                    foreach($this->data['Path']['Paths'] as $path){
                        $paths[] = $path;
                    }
                }

                if(isset($this->data['path']) && !empty($this->data['path'])){
                    $input_paths = explode(",", $this->data['path']);
                    if(is_array($input_paths) && !empty($input_paths)){
                        foreach($input_paths as $path){
                            $path = trim($path);
                            if(substr($path, 0, 4) == 'img/'){
                                $path = "/".$path;
                            }
                            $paths[] = $path;
                        }
                    }
                }


                $data = $this->Aws->cf_create_invalidation($this->data['distribution_id'],$paths);
                $this->redirect($this->referer());
                break;
        }

        $this->set('data', $data);
        $this->render(self::CF.$view);
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
