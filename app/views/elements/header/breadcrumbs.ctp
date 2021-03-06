<?php

$max_crumb_length = 40; // not including the standard crumb
//standard-crumb
//$this->Html->addCrumb('myZeitung', array('controller' => 'home', 'action' => 'index'), array('escape' => false));

if($this->params['controller'] == 'posts'){

	 if($this->params['action'] == 'add'){
         $this->Html->addCrumb(__('Posts', true), array('controller' => 'posts', 'action' => 'index'), array('escape' => false));
	 	$this->Html->addCrumb(__('Create', true), null , array('escape' => false));
	 }elseif($this->params['action'] == 'index'){	
	 	$this->Html->addCrumb(__('Posts', true),null, array('escape' => false));
	 }elseif($this->params['action'] == 'edit'){
        $this->Html->addCrumb(__('Posts', true), array('controller' => 'posts', 'action' => 'index'), array('escape' => false));
	 	$this->Html->addCrumb(__('Edit', true), null , array('escape' => false));
	 }elseif($this->params['action'] == 'view'){
        $this->Html->addCrumb(__('Posts', true), array('controller' => 'posts', 'action' => 'index'), array('escape' => false));
	 	$this->Html->addCrumb($user['User']['username'], array('controller' => 'users', 'action' => 'view', 'username' => strtolower($user['User']['username'])) , array('escape' => false));
        $free_crumb_length = $max_crumb_length - strlen($user['User']['username']);
        if(strlen($post['Post']['title']) > $free_crumb_length){
            $crumbtext = substr($post['Post']['title'],0,$free_crumb_length).'...';
        }else{
            $crumbtext = $post['Post']['title'];
        }
	 	$this->Html->addCrumb($crumbtext, null , array('escape' => false));
	 }
		 
}elseif($this->params['controller'] == 'users'){
	 //$this->Html->addCrumb(__('Users', true), array('controller' => 'Users', 'action' => 'index'), array('escape' => false));

	 if($this->params['action'] == 'add'){
	 	$this->Html->addCrumb(__('Register', true), null , array('escape' => false));
     }elseif($this->params['action'] == 'login'){
	 	$this->Html->addCrumb(__('Login', true), null , array('escape' => false));
     }elseif($this->params['action'] == 'accInvitations'){
	 	$this->Html->addCrumb(__('Invitations', true), null , array('escape' => false));
     }elseif($this->params['action'] == 'forgotPassword'){
	 	$this->Html->addCrumb(__('I forgot my password', true), null , array('escape' => false));
	 }elseif($this->params['action'] == 'index'){	
	 	$this->Html->addCrumb(__('Authors', true), null , array('escape' => false));
	 }elseif(in_array($this->params['action'],array('accSocial','accGeneral', 'accImage', 'accPrivacy', 'accAboutMe'))){
	 	$this->Html->addCrumb(__('Account Settings', true), null , array('escape' => false));	
	 }elseif($this->params['action'] == 'view'){
         if(isset($user['User']['username'])){
            $this->Html->addCrumb(__('Authors', true), array('controller' => 'users' , 'action' => 'index') , array('escape' => false));
	 	    $this->Html->addCrumb($user['User']['username'], array('controller' => 'users', 'action' => 'view', 'username' => strtolower($user['User']['username'])) , array('escape' => false));
            $this->Html->addCrumb(__('Posts', true),null , array('escape' => false));
         }
	 }elseif($this->params['action'] == 'viewSubscriptions'){
         if(isset($user['User']['username'])){
            $this->Html->addCrumb(__('Authors', true), array('controller' => 'users' , 'action' => 'index') , array('escape' => false));
            $this->Html->addCrumb($user['User']['username'], array('controller' => 'users', 'action' => 'view', 'username' => strtolower($user['User']['username'])) , array('escape' => false));
            if($this->params['own_paper'] == Paper::FILTER_OWN){
                $this->Html->addCrumb(__('Papers', true),  null , array('escape' => false));
            }elseif($this->params['own_paper'] == Paper::FILTER_SUBSCRIBED){
                $this->Html->addCrumb(__('Subscribed Papers', true),  null , array('escape' => false));
            }
         }
	 }

}elseif($this->params['controller'] == 'papers'){
	if($this->params['action'] == 'index'){
		$this->Html->addCrumb(__('Papers', true), null , array('escape' => false));
	}elseif($this->params['action'] == 'view'){
        $this->Html->addCrumb(__('Papers', true), array('controller' => 'papers' , 'action' => 'index') , array('escape' => false));
		$this->Html->addCrumb($paper['User']['username'],  array('controller' => 'users', 'action' => 'view', 'username' => strtolower($paper['User']['username']))  , array('escape' => false));
        $free_crumb_length = $max_crumb_length - strlen($paper['User']['username']);
       if(strlen($paper['Paper']['title']) > $free_crumb_length){
            $crumbtext = substr($paper['Paper']['title'],0,$free_crumb_length).'...';
        }else{
            $crumbtext = $paper['Paper']['title'];
        }
		$this->Html->addCrumb($crumbtext, null , array('escape' => false));
	
    }elseif($this->params['action'] == 'edit'){
        $this->Html->addCrumb(__('Papers', true), array('controller' => 'papers' , 'action' => 'index') , array('escape' => false));
		$this->Html->addCrumb(__('Edit',true), null  , array('escape' => false));
     }elseif($this->params['action'] == 'add'){
        $this->Html->addCrumb(__('Papers', true), array('controller' => 'papers' , 'action' => 'index') , array('escape' => false));
		$this->Html->addCrumb(__('Create',true), null  , array('escape' => false));
	}
}elseif($this->params['controller'] == 'search'){
	if($this->params['action'] == 'index'){
		$this->Html->addCrumb(__('Search Results', true), null , array('escape' => false));
	}
}elseif($this->params['controller'] == 'conversations'){
	if($this->params['action'] == 'index'){
		$this->Html->addCrumb(__('Messages', true), null , array('escape' => false));
	}elseif($this->params['action'] == 'add'){
		$this->Html->addCrumb(__('Messages', true), array('controller' => 'conversations', 'action' => 'index') , array('escape' => false));
		$this->Html->addCrumb(__('New Message', true), null , array('escape' => false));
	}elseif($this->params['action'] == 'view'){	
		$this->Html->addCrumb(__('Messages', true), array('controller' => 'conversations', 'action' => 'index') , array('escape' => false));
		$this->Html->addCrumb($conversation['Conversation']['title'], null , array('escape' => false));
	}
}

echo $this->Html->getCrumbs(' &gt; ');
?>



<?php  //  $this->params['action'] == 'index'
        //  $this->params['pass']['0'] ?>