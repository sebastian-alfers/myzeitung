<?php $this->MzJavascript->link('global/upload'); ?>
<?php $this->MzJavascript->link('user/account'); ?>


<?php echo $this->element('global/upload/modal'); ?>

<ul class="my-account-nav">
<fieldset>
<legend><?php echo __('Communication', true);?></legend>

    <?php if($this->params['controller'] == 'conversations' && ($this->params['action'] == 'index' || $this->params['action'] == 'view')):?><li class="active"><?php else:?><li><?php endif;?>
    <?php echo $this->Html->link('<span class="icon icon-messages"></span>'.__('Messages', true), array('controller' => 'conversations', 'action' => 'index'), array('escape' => false,));?></li>

    <?php if($this->params['controller'] == 'users' && $this->params['action'] == 'accInvitations'):?><li class="active"><?php else:?><li><?php endif;?>
    <?php echo $this->Html->link('<span class="icon icon-invitations"></span>'.__('Invitations', true), array('controller' => 'users', 'action' => 'accInvitations'), array('escape' => false, 'id' => 'link-invitations'));?></li>


</fieldset>
<fieldset>
<legend><?php echo __('Account Settings', true);?></legend>

    <?php if($this->params['controller'] == 'users' && $this->params['action'] == 'accAboutMe'):?><li class="active"><?php else:?><li><?php endif;?>
    <?php echo $this->Html->link('<span class="icon icon-about"></span>'.__('About Me', true), array('controller' => 'users', 'action' => 'accAboutMe'), array('escape' => false,));?></li>


    <?php if($this->params['controller'] == 'users' && $this->params['action'] == 'deleteProfilePicture'):?><li id="add_image" class="active"><?php else:?><li id="add_image"><?php endif;?>
    <span class="icon icon-profilpic"></span><a><?php __('Profile Picture'); ?></a>
</li>

    <?php if($this->params['controller'] == 'users' && ($this->params['action'] == 'accGeneral' || $this->params['action'] == 'accDelete')):?><li class="active" id="acc-general"><?php else:?><li id="acc-general"><?php endif;?>
    <?php echo $this->Html->link('<span class="icon icon-general"></span>'.__('General Settings', true), array('controller' => 'users', 'action' => 'accGeneral'), array('escape' => false,));?></li>

    <?php if($this->params['controller'] == 'users' && $this->params['action'] == 'accPrivacy'):?><li class="active" id="acc-privacy"><?php else:?><li id="acc-privacy"><?php endif;?>
    <?php echo $this->Html->link('<span class="icon icon-privacy"></span>'.__('Privacy', true), array('controller' => 'users', 'action' => 'accPrivacy'), array('escape' => false,));?></li>


    <?php if($this->params['controller'] == 'users' && $this->params['action'] == 'accSocial'):?><li class="active" id="acc-social"><?php else:?><li id="acc-social"><?php endif;?>
    <?php echo $this->Html->link('<span class="icon icon-social-media"></span>'.__('Social Media', true), array('controller' => 'users', 'action' => 'accSocial'), array('escape' => false, 'id' => 'link-social-media'));?></li>

    <?php if($this->params['controller'] == 'users' && $this->params['action'] == 'accRssImport'):?><li class="active" id="acc-rss"><?php else:?><li id="acc-rss"><?php endif;?>
    <?php echo $this->Html->link('<span class="icon icon-rss-import"></span>'.__('Rss Import', true), array('controller' => 'users', 'action' => 'accRssImport'), array('escape' => false, 'id' => 'link-rss-import'));?></li>


</fieldset>
  <fieldset>
<legend><?php echo __('Change Language', true);?></legend>

        <?php echo $this->element('locale/switch', array('locale' => $this->Session->read('Auth.Setting.user.default.locale.value'))); ?>

    </fieldset>
    <?php /* <hr />
    <?php echo $this->element('invite/button');  */?>
</ul>

<?php
//debug($_SESSION);
//die();
?>

