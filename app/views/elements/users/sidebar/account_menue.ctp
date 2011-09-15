<hr />
<?php
echo $html->script('global/upload');
echo $this->element('global/modal_upload',
                     array('title'  => 'Upload new Profile Picture',
                           'hash'   => $hash,
                           'model'  => 'User',
                           'model_id'=> $user_id,
                           'submit' => array('controller' => 'users', 'action' => 'accImage')));


?>

<ul class="my-account-nav">
    <h6><?php echo __('Communication', true);?></h6>

    <?php if($this->params['controller'] == 'conversations' && ($this->params['action'] == 'index' || $this->params['action'] == 'view')):?><li class="active"><?php else:?><li><?php endif;?>
    <?php echo $this->Html->link('<span class="icon icon-messages"></span>'.__('Messages', true), array('controller' => 'conversations', 'action' => 'index'), array('escape' => false,));?></li>

    <?php if($this->params['controller'] == 'users' && $this->params['action'] == 'accInvitations'):?><li class="active"><?php else:?><li><?php endif;?>
    <?php echo $this->Html->link('<span class="icon icon-invitations"></span>'.__('Invitations', true), array('controller' => 'users', 'action' => 'accInvitations'), array('escape' => false,));?></li>

    <?php //echo $this->element('invite/button', array('model' => 'user', 'complain_target_id' => $user['User']['id'])); ?>

     <hr />

    <h6><?php echo __('Account Settings', true);?></h6>
    <?php if($this->params['controller'] == 'users' && $this->params['action'] == 'accAboutMe'):?><li class="active"><?php else:?><li><?php endif;?>
    <?php echo $this->Html->link('<span class="icon icon-about"></span>'.__('About Me', true), array('controller' => 'users', 'action' => 'accAboutMe'), array('escape' => false,));?></li>


    <?php if($this->params['controller'] == 'users' && $this->params['action'] == 'deleteProfilePicture'):?><li id="add_image" class="active"><?php else:?><li id="add_image"><?php endif;?>
    <span class="icon icon-profilpic"></span><a><?php __('Profile Picture'); ?></a>
    </li>

	<?php if($this->params['controller'] == 'users' && ($this->params['action'] == 'accGeneral' || $this->params['action'] == 'accDelete')):?><li class="active"><?php else:?><li><?php endif;?>
    <?php echo $this->Html->link('<span class="icon icon-general"></span>'.__('General Settings', true), array('controller' => 'users', 'action' => 'accGeneral'), array('escape' => false,));?></li>
	
    <?php if($this->params['controller'] == 'users' && $this->params['action'] == 'accPrivacy'):?><li class="active"><?php else:?><li><?php endif;?>
    <?php echo $this->Html->link('<span class="icon icon-privacy"></span>'.__('Privacy', true), array('controller' => 'users', 'action' => 'accPrivacy'), array('escape' => false,));?></li>

    <?php if($this->params['controller'] == 'users' && $this->params['action'] == 'accSocial'):?><li class="active"><?php else:?><li><?php endif;?>
    <?php echo $this->Html->link('<span class="icon icon-social-media"></span>'.__('Social Media', true), array('controller' => 'users', 'action' => 'accSocial'), array('escape' => false,));?></li>






<?php /*?>		<li><a href=""><span class="icon icon-mynews"></span>Meine Zeitungen</a></li>
    <li><a href=""><span class="icon icon-articles"></span>Meine Artikel</a></li>
    <li><a href=""><span class="icon icon-mymessages"></span>Meine Nachrichten</a></li> <?php */?>
</ul>

