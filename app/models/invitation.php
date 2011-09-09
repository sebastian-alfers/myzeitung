<?php
class Invitation extends AppModel {
	var $name = 'Invitation';
	//The Associations below have been created with all possible keys, those that are not needed can be removed
    const STANDARD_TITLE_DEU = "Einladung zu myZeitung von %s";
    const STANDARD_TITLE_ENG = "Invitation to myZeitung from %s";
    const STANDARD_TEXT_ENG = 'Hello, i just invited you to myZeitung. (auto-generated message)';
    const STANDARD_TEXT_DEU = 'Hallo, ich habe Sie soeben zu myZeitung eingeladen. (automatisch generierte Nachricht)';
	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	var $hasMany = array(
		'Invitee' => array(
			'className' => 'Invitee',
			'foreignKey' => 'invitation_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

    function beforeSave($options){
       

        return true;
    }

    function afterSave($created){
       App::import('model','Invitee');
       $this->Invitee = new Invitee();

        if(isset($this->data['Invitation']['email']) && count($this->data['Invitation']['email']) > 0){
            foreach($this->data['Invitation']['email'] as $inviteeEmail){
                $inviteeData['email'] = $inviteeEmail;
                $inviteeData['invitation_id'] = $this->id;
                $inviteeData['reminder_count'] = 0;
                $this->Invitee->create();
                $this->Invitee->save($inviteeData);
            }
        }
    }

}
