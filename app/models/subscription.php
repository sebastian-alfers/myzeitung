<?php
class Subscription extends AppModel {
	var $name = 'Subscription';

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Paper' => array(
			'className' => 'Paper',
			'foreignKey' => 'paper_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterCache' => true 
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterCache' => true,
			'counterScope' => array('own_paper' => false),
				)
	);
    function __construct(){
        parent::__construct();
    }
    function disable(){

        if($this->data['Subscription']['enabled'] == true){
            //disable subscription
            $this->data['Subscription']['enabled'] = false;
            $this->save($this->data);

            return true;
        }
        //already disabled
        return false;
    }
    function enable(){

        if($this->data['Subscription']['enabled'] == false){


            //disable subscription
            $this->data['Subscription']['enabled'] = true;
            $this->save($this->data);

            return true;
        }
        //already enabled
        return false;
    }
}

?>