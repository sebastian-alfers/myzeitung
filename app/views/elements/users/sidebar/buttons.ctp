<?php
$link = '/login';
if($session->read('Auth.User.id')){
    $link = '#';
}
if($user['User']['id'] != $session->read('Auth.User.id')){
   $subscribe_button_text=  __('Subscribe Author',true);
}else{
   $subscribe_button_text=  __('Subscribe me',true);
}
?>
<fieldset>
<legend><?php echo __('Options', true);?></legend>
<ul>
<?php if(($this->params['controller'] == 'users' && $this->params['action'] == 'view') || ($this->params['controller'] == 'posts' && $this->params['action'] == 'view')): ?>

    <li id="user-sidebar-subscribe-btn"><a href="<?php echo $link; ?>" class="btn subscribe-user" id="<?php echo $user['User']['id']; ?>" help-text="<strong>Abonnieren</strong><br />Klicke auf den Button, um den Autor in eine deiner Zeitungen zu abonnieren. Anschließend erscheinen alle seine aktuellen und zukünftigen Artikel in deiner Zeitung.<br />Du hast die möglichkeit, deine Zeitung und eine Kategorie, wenn vorhanden, zu wählen. Hat der der Benutzer ein oder mehrere Themen, kannst du ein spezielles Thema abonnieren."><span>+</span><?php echo $subscribe_button_text; ?></a></li>
<?php endif;?>
<embed src="http://www.youtube.com/v/qPE2fHs5-_I" type="application/x-shockwave-flash" wmode="transparent" width="425" height="355"></embed>
<?php if($user['User']['id'] != $session->read('Auth.User.id')): // - cannot send a message to himself ?>

	<?php if(isset($user['Setting']['user']['default']['allow_messages']) && $user['Setting']['user']['default']['allow_messages']['value'] == true): ?>
		<li>
            <a href="#" class="btn gray new-conversation"><span class="send-icon"></span><?php __('Send Message'); ?></a>
        </li>
	<?php endif; ?>
<?php elseif($this->params['controller'] == 'users' && $this->params['action'] == 'viewSubscriptions'): ?>

	    <li><?php echo $this->Html->link('<span>+</span>'.__('New Paper', true), array('controller' => 'papers',  'action' => 'add'), array('escape' => false, 'class' => 'btn', 'id' => 'new-paper'));?></li>
<?php endif; ?>
</ul>
</fieldset>