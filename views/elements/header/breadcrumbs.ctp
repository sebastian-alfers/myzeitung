<?php

//standard-crumb
$this->Html->addCrumb('myZeitung', array('controller' => 'home', 'action' => 'index'), array('escape' => false));

if($this->params['controller'] == 'posts'){

	 if($this->params['action'] == 'add'){
	 	$this->Html->addCrumb(__('New Post', true), null , array('escape' => false));	
	 }elseif($this->params['action'] == 'index'){	
	 	$this->Html->addCrumb(__('Posts', true), array('controller' => 'posts', 'action' => 'index'), array('escape' => false));	
	 }elseif($this->params['action'] == 'edit'){
	 	$this->Html->addCrumb(__('Edit Post', true), null , array('escape' => false));	
	 }elseif($this->params['action'] == 'view'){
	 	$this->Html->addCrumb($user['User']['username'], array('controller' => 'users', 'action' => 'view', $user['User']['id']) , array('escape' => false));	
	 	$this->Html->addCrumb($post['Post']['title'], null , array('escape' => false));	
	 }
		 
		 
		 
}elseif($this->params['controller'] == 'users'){
	 //$this->Html->addCrumb(__('Users', true), array('controller' => 'Users', 'action' => 'index'), array('escape' => false));

	 if($this->params['action'] == 'add'){
	 	$this->Html->addCrumb(__('Register', true), null , array('escape' => false));
	 }elseif($this->params['action'] == 'index'){	
	 	$this->Html->addCrumb(__('Users', true), null , array('escape' => false));
	 }elseif(in_array($this->params['action'],array('accGeneral', 'accImage', 'accPrivacy', 'accAboutMe'))){
	 	$this->Html->addCrumb(__('Account Settings', true), null , array('escape' => false));	
	 }elseif($this->params['action'] == 'view'){
	 	$this->Html->addCrumb($user['User']['username'],  null , array('escape' => false));	
	 }elseif($this->params['action'] == 'viewSubscriptions'){
	 	$this->Html->addCrumb($user['User']['username'], array('controller' => 'users', 'action' => 'view', $user['User']['id']) , array('escape' => false));	
	 	$this->Html->addCrumb(__('Subscriptions', true),  null , array('escape' => false));	
	 }

}elseif($this->params['controller'] == 'papers'){
	if($this->params['action'] == 'index'){
		$this->Html->addCrumb(__('Papers', true), null , array('escape' => false));
	}elseif($this->params['action'] == 'view'){	
		$this->Html->addCrumb(__('Papers', true),  array('controller' => 'papers', 'action' => 'index',)  , array('escape' => false));
		$this->Html->addCrumb($paper['Paper']['title'], null , array('escape' => false));	
	}
}elseif($this->params['controller'] == 'search'){
	if($this->params['action'] == 'index'){
		$this->Html->addCrumb(__('Search Results', true), null , array('escape' => false));
	}
}elseif($this->params['controller'] == 'conversations'){
	if($this->params['action'] == 'index'){
		$this->Html->addCrumb(__('Conversations', true), null , array('escape' => false));
	}elseif($this->params['action'] == 'add'){
		$this->Html->addCrumb(__('Conversations', true), array('controller' => 'conversations', 'action' => 'index') , array('escape' => false));
		$this->Html->addCrumb(__('New Message', true), null , array('escape' => false));
	}elseif($this->params['action'] == 'view'){	
		$this->Html->addCrumb(__('Conversations', true), array('controller' => 'conversations', 'action' => 'index') , array('escape' => false));
		$this->Html->addCrumb($conversation['Conversation']['title'], null , array('escape' => false));
	}
}




echo $this->Html->getCrumbs(' &gt; ');
?>



<?php  //  $this->params['action'] == 'index'
        //  $this->params['pass']['0'] ?>