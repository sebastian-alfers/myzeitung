<?php
class ConversationsController extends AppController {

	var $name = 'Conversations';
	var $uses = array('Conversation','ConversationUser','ConversationMessage');

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
						$userData['ConversationUser']['status'] = ConversationUser::STATUS_CONVERSATION_ACTIVE;
						$userData['ConversationUser']['last_viewed_message'] = $this->ConversationMessage->id;		
						$this->ConversationUser->save($userData);	
					}
					//updating the last message id of the new conversation
					$this->Conversation->save(array('id' => $this->Conversation->id, 'last_message_id' => $this->ConversationMessage->id));
					$this->Session->setFlash(__('The Message has been sent', true));
				}
				
				$this->redirect(array('controller' => 'users', 'action' => 'view', $this->Auth->user('id')));
			} else {
				$this->Session->setFlash(__('The Message could not be sent. Please, try again.', true));
			}
		}
		
		//$this->set('recipent_id', $recipent_id);
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
				} else {
					$this->Session->setFlash(__('The Message could not be sent, please try again', true));
				}
				$this->redirect(array('controller' => 'users', 'action' => 'view', $this->Auth->user('id')));
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
				
		} else {
			$this->Session->setFlash(__('You do not participate in this particular conversation.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'view', $this->Auth->user('id')));
		}
		$this->set('messages', $messages);
		
		$this->Conversation->contain();
		$this->set('conversation', $this->Conversation->read(array('title','created'),$conversation_id));
		$this->ConversationUser->contain('User.id','User.username', 'User.image');
		$this->set('users', $this->ConversationUser->find('all', array('conditions' => array('conversation_id' => $conversation_id))));
	}

	function index() {
		$user_id = $this->Auth->user('id');
        $options = array('conditions' => array('ConversationUser.status !='  => ConversationUser::STATUS_CONVERSATION_DELETED),
                        'group' => array('ConversationUser.conversation_id HAVING SUM(case when ConversationUser.`user_id` in (\''.$user_id.'\') then 1 else 0 end) = 1'),
                        'contain' => array('Conversation' => array('LastMessage')),
                        'order' => array('Conversation.last_message_id'=>'DESC'),
	        		//	'fields' => array('ConversationUser.status', 'ConversationUser.last_viewed_message')
                );
		$conversations = $this->ConversationUser->find('all', $options);
		foreach($conversations as &$conversation){
          	$this->ConversationUser->contain('User.image', 'User.username', 'User.id');
           	$conversation['Conversation']['ConversationUser'] = $this->ConversationUser->find('all', array('fields' => array(''), 'conditions' => array('conversation_id' => $conversation['Conversation']['id'])));
        }

        $this->set('conversations', $conversations);
	} 
}


?>