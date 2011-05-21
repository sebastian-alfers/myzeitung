<?php
class ConversationsController extends AppController {

	var $name = 'Conversations';
	var $uses = array('Conversation','ConversationUser','ConversationMessage');

    var $helpers = array('Image');

	function add($recipent_id = null){
		if (!empty($this->data)) {
			$this->Conversation->create();
			$recipents = explode(" ", $this->data['Conversation']['recipents']);
			$recipents[] = $this->data['Conversation']['user_id'];
			$conversationData['Conversation']['user_id'] = $this->data['Conversation']['user_id'];
			$conversationData['Conversation']['title'] = $this->data['Conversation']['title'];
 			
			//saving the conversation itself
			if ($this->Conversation->save($conversationData)) {
				$this->ConversationMessage->create();
				$messageData['ConversationMessage']['conversation_id'] = $this->Conversation->id;
				$messageData['ConversationMessage']['user_id'] = $conversationData['Conversation']['user_id'];
				$messageData['ConversationMessage']['message'] = $this->data['Conversation']['message'];
				//saving the (first) message of the conversation
				if ($this->ConversationMessage->save($messageData)) {
					//saving all recipents + the sender as conversationusers
					foreach($recipents as $recipent){
						$this->ConversationUser->create();
						$userData['ConversationUser']['conversation_id'] = $this->Conversation->id;
						$userData['ConversationUser']['user_id'] = $recipent;	
						if($recipent == $this->Auth->user('id')){
							$userData['ConversationUser']['status'] = Conversation::STATUS_ACTIVE;
						} else {
							$userData['ConversationUser']['status'] = Conversation::STATUS_NEW;
						}	
						
						$userData['ConversationUser']['last_viewed_message'] = $this->ConversationMessage->id;		
						$this->ConversationUser->save($userData);	
					}
					//updating the last message id of the new conversation
					$this->Conversation->save(array('id' => $this->Conversation->id, 'last_message_id' => $this->ConversationMessage->id));
					$this->Session->setFlash(__('The Message has been sent', true));
				}
				
				$this->redirect($this->referer());
			} else {
				$this->Session->setFlash(__('The Message could not be sent. Please, try again.', true));
			}
		}
		
		$this->User->contain();
		$this->set('recipent', $this->User->read(array('id', 'username', 'name'), $recipent_id));
		$this->set('user_id', $this->Auth->user('id'));	
	}
	
	function reply($conversation_id = null){
		if(!empty($this->data)){
			$conversation_id = $this->data['Conversation']['id'];
			//check if user is allowed to answer to this conversation
			$this->ConversationUser->contain();
			if($this->ConversationUser->find('count', array('conditions' => array('conversation_id' => $conversation_id, 'user_id' => $this->Auth->user('id'))))){
				$this->ConversationMessage->create();
				//saving the reply
				$messageData['ConversationMessage']['conversation_id'] = $this->data['Conversation']['id'];
				$messageData['ConversationMessage']['user_id'] = $this->Auth->user('id');
				$messageData['ConversationMessage']['message'] = $this->data['Conversation']['message'];
				if($this->ConversationMessage->save($messageData)){
					//update last_message_id of the conversation
					$this->Conversation->save(array('id' => $conversation_id, 'last_message_id' => $this->ConversationMessage->id));
					$this->Session->setFlash(__('The Message has been sent', true));	
					//update ConversationUser Status for recipents of the reply to "new message"
					$this->ConversationUser->contain();
					$recipents = $this->ConversationUser->findAllByConversationId($this->data['Conversation']['id']);
					foreach($recipents as $recipent){
						//change status only for recipents - not for author of the reply
						if($recipent['ConversationUser']['user_id'] != $this->Auth->user('id')){
							$recipent['ConversationUser']['status'] = Conversation::STATUS_NEW;
						}else {
							$recipent['ConversationUser']['status'] = Conversation::STATUS_REPLIED;
						}
						$this->ConversationUser->save($recipent);
					}
					
				} else {
					$this->Session->setFlash(__('The Message could not be sent, please try again', true));
				}
				$this->redirect($this->referer());
			} else {
				$this->Session->setFlash(__('You do not participate in this particular conversation. You are not allowed to answer', true));
				$this->log('Conversation - Reply: User-id '.$this->Auth->user('id').' tried to reply to conversation '.$conversation_id.' which he is not part of!');
			}	
		}
		$this->set('conversation_id', $conversation_id);
		$this->set('user_id', $this->Auth->user('id'));	
		
		
	}
	function view($conversation_id = null){
		//check if user is allowed to answer to this conversation
		$this->ConversationUser->contain();
		if($this->ConversationUser->find('count', array('conditions' => array('conversation_id' => $conversation_id, 'user_id' => $this->Auth->user('id'))))){
	
			$this->ConversationMessage->contain('User.image','User.username', 'User.id');
			$messages = $this->ConversationMessage->find('all', array('conditions' => array('ConversationMessage.conversation_id' => $conversation_id), 'order' => 'ConversationMessage.created'));
			
			//update status of the conversation for reading user
			$this->ConversationUser->contain();
			$userData = $this->ConversationUser->find('first', array('fields' =>array('id', 'status', 'last_viewed_message'), 'conditions' => array('user_id' =>  $this->Auth->user('id'), 'conversation_id' => $conversation_id)));
			$userData['ConversationUser']['status'] = conversation::STATUS_ACTIVE;
			$userData['ConversationUser']['last_viewed_message'] = $messages[count($messages) -1]['ConversationMessage']['id'];
			$this->ConversationUser->save($userData);
			
		} else {
			$this->Session->setFlash(__('You do not participate in this particular conversation.', true));
			$this->redirect($this->referer());
		}
		$this->set('messages', $messages);
		
		// setting the messagecount (again) because: if you view an conversation which has had the status "new", the message_count must be reset, because this specific conversation is not new anymore (updated above)
		parent::setConversationCount();
		
		$this->Conversation->contain();
		$this->set('conversation', $this->Conversation->read(array('title','created'),$conversation_id));
		$this->ConversationUser->contain('User.id','User.username', 'User.image');
		$this->set('users', $this->ConversationUser->find('all', array('conditions' => array('conversation_id' => $conversation_id))));
	}

	function index() {
		$user_id = $this->Auth->user('id');


        $options = array('conditions' => array('ConversationUser.status <'  => Conversation::STATUS_REMOVED, 'ConversationUser.user_id' => $user_id),
                  	    'group' => array('ConversationUser.conversation_id HAVING SUM(case when ConversationUser.`user_id` in (\''.$user_id.'\') then 1 else 0 end) = 1'),
                        'contain' => array('Conversation' => array()),
                        'order' => array('Conversation.last_message_id'=>'DESC'),
                );
		$conversations = $this->ConversationUser->find('all', $options);
		foreach($conversations as &$conversation){
          	$this->ConversationUser->contain('User.image', 'User.username', 'User.id');
           	$conversation['Conversation']['ConversationUser'] = $this->ConversationUser->find('all', array('fields' => array(''), 'conditions' => array('conversation_id' => $conversation['Conversation']['id'])));
		  	$this->ConversationMessage->contain();
           	$lastMessage = $this->ConversationMessage->read(array('user_id','message','created'), $conversation['Conversation']['last_message_id']);
        	$conversation['Conversation']['LastMessage'] = $lastMessage['ConversationMessage'];
		}

        $this->set('conversations', $conversations);
	} 
	
	function remove($conversation_id){
		//check if user is allowed to remove to this conversation
		$this->ConversationUser->contain();
		if($this->ConversationUser->find('count', array('conditions' => array('conversation_id' => $conversation_id, 'user_id' => $this->Auth->user('id'))))){

			//update status of the conversation for removing user
			$this->ConversationUser->contain();
			$userData = $this->ConversationUser->find('first', array('fields' =>array('id', 'status', 'last_viewed_message'), 'conditions' => array('user_id' =>  $this->Auth->user('id'), 'conversation_id' => $conversation_id)));
			$userData['ConversationUser']['status'] = conversation::STATUS_REMOVED;
			$this->ConversationUser->save($userData);
		} else {
			$this->Session->setFlash(__('You do not participate in this particular conversation.', true));
		}
		$this->redirect($this->referer());
	}
	
}


?>