<?php


class InvitationsController extends AppController {

	var $name = 'Invitations';

	var $components = array('RequestHandler', 'JqImgcrop', 'Email',);
	var $uses = array('Invitation', 'Invitee');
	var $helpers = array('MzText', 'MzTime', 'Image', 'Js' => array('Jquery'), 'Javascript');


	function add(){
        $this->data = array();
        $this->data['Invitation']['email'] = array('tim.wiegard@myzeitung.de', 'tim.wiegard@googlemail.de','tim.wiegard@gmail.com');
        $this->data['Invitation']['text'] = 'hey du typ. geh auf myzeitung und ich sach dir was los ist';

       if (!empty($this->data)) {
           $this->data['Invitation']['user_id'] = $this->Session->read('Auth.User.id');
			$this->Invitation->create();
			if ($this->Invitation->save($this->data, false, array('user_id', 'text'))) {
                $registeredCount = 0;
                App::import('model','Conversation');
                $this->Conversation = new Conversation();
                App::import('model','User');
                $this->User = new User();
                $invitee = $this->Invitee->getEmailAddresses($this->Invitation->id);
                foreach($invitee as $recipient){
                    $this->_sendInvitationEmail($recipient['Invitee']['email'], $this->data['Invitation']['text'], $this->data['Invitation']['user_id']);

                    $this->User->contain();
                    $user = array();
                    $user = $this->User->find('first', array('conditions' => array('email' => $recipient['Invitee']['email'])));
                    if(isset($user['User']['id']) && $user['User']['id'] != $this->Session->read('Auth.User.id')){
                        $this->Conversation->generateConversationForRegisteredInvitee($this->data, $user['User']['id']);
                        $registeredCount++;
                    }
                }
                $flashMessage =__('Your Invitation has been saved and emails have been sent to the Invitee.',true);
                if($registeredCount >0 ){
                    $flashMessage .= ' '.sprintf(__n('%d of them is already registered','%d of them are already registered.',$registeredCount,true),$registeredCount);
                }
                $this->Session->setFlash($flashMessage, 'default', array('class' => 'success'));
                $this->redirect('/');

			} else {
				$this->Session->setFlash(__('The Invitation could not be saved. Please, try again.', true));
                $this->redirect('/');
			}
		}
	}


    protected function _sendInvitationEmail($recipient_email, $text, $sender_id) {

        $sender = array();

        $this->User->contain();
        $sender = $this->User->read(array('id', 'username', 'name'), $sender_id);


        $this->set('sender', $sender);
        $this->set('recipient', array('User' => array('email' => $recipient_email)));

        $sender_name = $sender['User']['username'];
        if(!empty($sender['User']['name'])){
            $sender_name .= ' - '.$sender['User']['name'];
        }
        $this->_sendMail($recipient_email, sprintf(__('Invitation to myZeitung from %s',true),$sender_name), 'invitation');


    }



}
