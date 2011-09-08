<?php
class Invitee extends AppModel {
	var $name = 'Invitee';
	var $useTable = 'invitee';

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Invitation' => array(
			'className' => 'Invitation',
			'foreignKey' => 'invitation_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);


    function getEmailAddresses($invitation_id){

        $this->contain();
        return  $this->findAllByInvitationId($invitation_id);

    }
}
