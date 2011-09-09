<?php
class Conversation extends AppModel{
	var $name = 'Conversation';
	var $displayField = 'title';
	
	// new means: new message since last read in this conversation
	const STATUS_NEW = 1;
	// active means: not deleted and no new messages since last read
	const STATUS_ACTIVE = 2;
	// replied means: active + replied to the last status (if a new message arrives, the status will be 'new' again)
	const STATUS_REPLIED = 3;
	// removed means = hidden -> will be set back to status_new after a new reply
	const STATUS_REMOVED = 4;
	
	var $validate = array(
		'user_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'last_message_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'allow_add' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'count' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $hasMany = array(
		'ConversationMessage' => array(
			'className' => 'ConversationMessage',
			'foreignKey' => 'conversation_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => 'created',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'ConversationUser' => array(
			'className' => 'ConversationUser',
			'foreignKey' => 'conversation_id',
			'dependent' => true,
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

    function generateConversationForRegisteredInvitee($invitation, $invitee_id){

        App::import('model','Conversation');
        $this->Conversation = new Conversation();
        $conversationData['Conversation'] = array('user_id' => $invitation['Invitation']['user_id'],
                                                   'title' => 'Einladung zu myZeitung / Invitation to myZeitung');
        $this->Conversation->create();
        if($this->Conversation->save($conversationData)){
            //add conversation message
             App::import('model','ConversationMessage');
            $this->ConversationMessage = new ConversationMessage();
            $messageData['ConversationMessage']['conversation_id'] = $this->Conversation->id;
            $messageData['ConversationMessage']['user_id'] = $invitation['Invitation']['user_id'];
            if(!empty($invitation['Invitation']['text'])){
                $messageData['ConversationMessage']['message'] = $invitation['Invitation']['text'];
            }else{
                $messageData['ConversationMessage']['message'] = Invitation::STANDARD_TEXT_DEU.' '.Invitation::STANDARD_TEXT_ENG;
            }
            //saving the (first) message of the conversation
            if ($this->ConversationMessage->save($messageData)) {
                //add conversation users
                App::import('model','ConversationUser');
                $this->ConversationUser = new ConversationUser();

                $recipients = array($invitation['Invitation']['user_id'],$invitee_id);
                foreach($recipients as $recipient){
                    $this->ConversationUser->create();
                    $userData['ConversationUser']['conversation_id'] = $this->Conversation->id;
                    $userData['ConversationUser']['user_id'] = $recipient;
                    if($recipient == $invitation['Invitation']['user_id']){
                        $userData['ConversationUser']['status'] = Conversation::STATUS_ACTIVE;
                    } else {
                        $userData['ConversationUser']['status'] = Conversation::STATUS_NEW;
                    }

                    $userData['ConversationUser']['last_viewed_message'] = $this->ConversationMessage->id;
                    $this->ConversationUser->save($userData);
                }
                //updating the last message id of the new conversation
                $this->Conversation->save(array('id' => $this->Conversation->id, 'last_message_id' => $this->ConversationMessage->id));

            }

        }
    }


	
}
?>