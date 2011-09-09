<?php


class InvitationsController extends AppController {

	var $name = 'Invitations';

	var $components = array('RequestHandler', 'JqImgcrop', 'Email',);
	var $uses = array('Invitation', 'Invitee');
	var $helpers = array('MzText', 'MzTime', 'Image', 'Js' => array('Jquery'), 'Javascript');


	function add(){
/*
        $this->data = array();
        $this->data['Invitation']['email'] = array('tim.wiegard@myzeitung.de', 'tim.wiegard@googlemail.com','tim.wiegard@gmail.com');
        $this->data['Invitation']['text'] = 'hey du typ. geh auf myzeitung und ich sach dir was los ist. hey du typ. geh auf myzeitung und ich sach dir was los ist. hey du typ. geh auf myzeitung und ich sach dir was los ist. hey du typ. geh auf myzeitung und ich sach dir was los ist. ';

*/
       if (!empty($this->data)) {
           //remove empty fields
           $this->data['Invitation']['email'] = array_filter($this->data['Invitation']['email']);

           //remove duplicated fields
           $this->data['Invitation']['email'] = array_unique($this->data['Invitation']['email']);

           //remove users email
           foreach($this->data['Invitation']['email'] as $key => $value){
               if($value == $this->Session->read('Auth.User.email')){
                   unset($this->data['Invitation']['email'][$key]);
               }
           }

           //$this->data['Invitation']['text'] = 'hey du typ. geh auf myzeitung und ich sach dir was los ist';
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

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for invitation', true));
			$this->redirect($this->referer());
		}
        $this->Invitation->contain();
        $invitation =  $this->Invitation->read(array('id','user_id'), $id);
        if($invitation['Invitation']['user_id'] == $this->Session->read('Auth.User.id')){
            if ($this->Invitation->delete($id, true)) {
                $this->Session->setFlash(__('Invitation deleted', true), 'default', array('class' => 'success'));
                $this->redirect(array('controller' => 'users', 'action'=>'accInvitations'));
            }
            $this->Session->setFlash(__('Invitation was not deleted', true));
            $this->redirect($this->referer());
        } else {
            $this->Session->setFlash(__('The Invitation does not belong to you.', true));
            $this->redirect($this->referer());
        }
	}

    function remindInvitee($id){
        if (!$id) {
			$this->Session->setFlash(__('Invalid id for invitee', true));
			$this->redirect($this->referer());
		}
        $this->Invitee->contain('Invitation');
        $invitee = $this->Invitee->read(null,$id);
        if($invitee['Invitation']['user_id'] == $this->Session->read('Auth.User.id')){
            $this->_sendInvitationEmail($invitee['Invitee']['email'],$invitee['Invitation']['text'], $this->Session->read('Auth.User.id'));
            $this->Session->setFlash(__('Reminder sent', true), 'default', array('class' => 'success'));
            $invitee['Invitee']['reminder_count']++;
            $this->Invitee->save($invitee,false,array('reminder_count'));
            $this->redirect($this->referer());
        }else {
            $this->Session->setFlash(__('The Invitation does not belong to you.', true));
            $this->redirect($this->referer());
        }

    }

    protected function _sendInvitationEmail($recipient_email, $text, $sender_id) {

        $sender = array();

        $this->User->contain();
        $sender = $this->User->read(array('id', 'username', 'name'), $sender_id);


        $this->set('sender', $sender);
        $this->set('recipient', array('User' => array('email' => $recipient_email)));
        $this->set('text',$text);

        $sender_name = $sender['User']['username'];
        if(!empty($sender['User']['name'])){
            $sender_name .= ' - '.$sender['User']['name'];
        }
        $this->_sendMail($recipient_email, sprintf(__('Invitation to myZeitung from %s',true),$sender_name), 'invitation');


    }



}
