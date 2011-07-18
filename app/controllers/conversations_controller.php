<?php
class ConversationsController extends AppController {

	var $name = 'Conversations';
	var $uses = array('User' ,'Conversation','ConversationUser','ConversationMessage');

    var $components = array('Email');

    var $helpers = array('Image', 'Time');

	function add($recipient_id = null){
		if(!$recipient_id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid recipient', true));
			$this->redirect($this->referer());
		}	
		
		if($recipient_id || !empty($this->data)){
			if(!$recipient_id){
				// @todo after multi-user conversations are supported, this must be changed to a multiuser validation
				$recipient_id = $this->data['Conversation']['recipients'];
			}
			$this->User->contain();
			$recipient = $this->User->read(array('id', 'username', 'name', 'allow_messages'), $recipient_id);
			if($recipient['User']['allow_messages'] == false){
				$this->Session->setFlash(__('This recipient does not acceppt messages.', true));
				$this->redirect(array('controller' => 'conversations', 'action' => 'index'));
			}
		}
		
		if (!empty($this->data)) {
			$this->Conversation->create();
			$recipients = explode(" ", $this->data['Conversation']['recipients']);
			$recipients[] = $this->data['Conversation']['user_id'];
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
					//saving all recipients + the sender as conversationusers
					foreach($recipients as $recipient){
						$this->ConversationUser->create();
						$userData['ConversationUser']['conversation_id'] = $this->Conversation->id;
						$userData['ConversationUser']['user_id'] = $recipient;
						if($recipient == $this->Auth->user('id')){
							$userData['ConversationUser']['status'] = Conversation::STATUS_ACTIVE;
						} else {
							$userData['ConversationUser']['status'] = Conversation::STATUS_NEW;
                            //send email to recipient
                            $this->_sendNewMessageEmail($userData['ConversationUser']['user_id'], $this->Auth->user('id'), $this->Conversation->id);
						}	
						
						$userData['ConversationUser']['last_viewed_message'] = $this->ConversationMessage->id;		
						$this->ConversationUser->save($userData);	
					}
					//updating the last message id of the new conversation
					$this->Conversation->save(array('id' => $this->Conversation->id, 'last_message_id' => $this->ConversationMessage->id));
					$this->Session->setFlash(__('The Message has been sent', true), 'default', array('class' => 'success'));
				}
				
				$this->redirect(array('controller' => 'conversations', 'action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Message could not be sent. Please, try again.', true));
			}
		}
		if(!($recipient_id)){
			
		}
		
		
		$this->set('recipient', $this->User->read(array('id', 'username', 'name', 'allow_messages'), $recipient_id));
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
					$this->Session->setFlash(__('The Message has been sent', true), 'default', array('class' => 'success'));	
					//update ConversationUser Status for recipients of the reply to "new message"
					$this->ConversationUser->contain();
					$recipients = $this->ConversationUser->findAllByConversationId($this->data['Conversation']['id']);
					foreach($recipients as $recipient){
						//change status only for recipients - not for author of the reply
						if($recipient['ConversationUser']['user_id'] != $this->Auth->user('id')){
							if($recipient['ConversationUser']['user_id']){
								//active users get status : new message
								$recipient['ConversationUser']['status'] = Conversation::STATUS_NEW;
                                 //send email to recipient

                                 $this->_sendNewMessageEmail($recipient['ConversationUser']['user_id'], $this->Auth->user('id'), $conversation_id);
							}else{
								//if there is a deleted user in a conversation the message won't get the status "new".
								$recipient['ConversationUser']['status'] = Conversation::STATUS_REMOVED;
							}
						}else {
							$recipient['ConversationUser']['status'] = Conversation::STATUS_REPLIED;
						}
						$this->ConversationUser->save($recipient);
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
	
			$this->ConversationMessage->contain('User.image','User.username', 'User.name', 'User.id');
			$messages = $this->ConversationMessage->find('all', array('conditions' => array('ConversationMessage.conversation_id' => $conversation_id), 'order' => 'ConversationMessage.created'));
			
			//update status of the conversation for reading user
			$this->ConversationUser->contain();
			$userData = $this->ConversationUser->find('first', array('fields' =>array('id', 'status', 'last_viewed_message'), 'conditions' => array('user_id' =>  $this->Auth->user('id'), 'conversation_id' => $conversation_id)));
			if($userData['ConversationUser']['status'] != conversation::STATUS_REPLIED){
				$userData['ConversationUser']['status'] = conversation::STATUS_ACTIVE;
				$userData['ConversationUser']['last_viewed_message'] = $messages[count($messages) -1]['ConversationMessage']['id'];
				$this->ConversationUser->save($userData);
			}
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
        $this->User->contain('Topic.id', 'Topic.name', 'Topic.post_count', 'Paper.id' , 'Paper.title', 'Paper.image');
        $this->set('user', $this->getUserForSidebar($this->Auth->user('id')));
	}

	function index() {
		$user_id = $this->Auth->user('id');

        $this->paginate = array('ConversationUser' => array(
                        'conditions' => array('ConversationUser.status <'  => Conversation::STATUS_REMOVED, 'ConversationUser.user_id' => $user_id),
                  //	    'group' => array('ConversationUser.conversation_id HAVING SUM(case when ConversationUser.`user_id` in (\''.$user_id.'\') then 1 else 0 end) = 1'),
                        'limit' => 10,
                        'contain' => array('Conversation' => array()),
                        'order' => array('Conversation.last_message_id'=>'DESC'),
                ));
       // debug($this->paginate("ConversationUser", array('ConversationUser.status <'  => Conversation::STATUS_REMOVED, 'ConversationUser.user_id' => $user_id)));die();
        // DONT DELETE cos i really dont know if it works without the group statement, which does not work in paginate.
    /*    $options = array(
                        'conditions' => array('ConversationUser.status <'  => Conversation::STATUS_REMOVED, 'ConversationUser.user_id' => $user_id),
                  	    'group' => array('ConversationUser.conversation_id HAVING SUM(case when ConversationUser.`user_id` in (\''.$user_id.'\') then 1 else 0 end) = 1'),
                        'contain' => array('Conversation' => array()),
                        'order' => array('Conversation.last_message_id'=>'DESC'),
                );*/
		//$conversations = $this->ConversationUser->find('all', $options);
        $conversations = $this->paginate("ConversationUser", array('ConversationUser.status <'  => Conversation::STATUS_REMOVED, 'ConversationUser.user_id' => $user_id));
		foreach($conversations as &$conversation){
          	$this->ConversationUser->contain('User.image', 'User.username' ,'User.name', 'User.id');
           	$conversation['Conversation']['ConversationUser'] = $this->ConversationUser->find('all', array('fields' => array(''), 'conditions' => array('conversation_id' => $conversation['Conversation']['id'])));
		  	$this->ConversationMessage->contain();
           	$lastMessage = $this->ConversationMessage->read(array('user_id','message','created'), $conversation['Conversation']['last_message_id']);
        	$conversation['Conversation']['LastMessage'] = $lastMessage['ConversationMessage'];
		}
        $this->User->contain('Topic.id', 'Topic.name', 'Topic.post_count', 'Paper.id' , 'Paper.title', 'Paper.image');
        $this->set('user', $this->getUserForSidebar($user_id));

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

    	/**
	 * reading user from session or db, depending of the view.
	 * IMPORTANT : containments must be defined in the action
	 * @param unknown_type $user_id
	 */
	protected function getUserForSidebar($user_id = ''){
		if($user_id == ''){
		//reading logged in user from session
			$user['User'] = $this->Session->read('Auth.User');
		} else {
		//reading user
			$user = $this->User->read(array('id','name','username','created','image' , 'allow_messages', 'allow_comments','description','posts_user_count','post_count','comment_count', 'content_paper_count', 'subscription_count', 'paper_count'), $user_id);
		}

		return $user;

	}

    /**
       * send an email to a user that got subscribed by another user.
       */
      protected function _sendNewMessageEmail($recipient_id, $sender_id ,$conversation_id) {
          $recipient = array();
          $sender = array();
          $conversation = array();

          $this->User->contain();
          $recipient = $this->User->read(array('id', 'username', 'name', 'email'), $recipient_id);
          $this->User->contain();
          $sender = $this->User->read(array('id', 'username', 'name'), $sender_id);
          $this->Conversation->contain(array('ConversationMessage' =>
                                        array('User' =>
                                             array('fields' => array('id', 'name', 'username', 'image')))));

          $conversation = $this->Conversation->read(null, $conversation_id);

          $this->set('sender', $sender);
          $this->set('recipient', $recipient);
          $this->set('conversation', $conversation);

          $this->_sendMail($recipient['User']['email'], __('New Message from', true).' '.$sender['User']['username'].' in Conversation '.$conversation['Conversation']['title'],'new_message');


      }



}



?>