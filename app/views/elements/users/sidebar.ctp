<?php if(isset($newConversation) && $newConversation === true): ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#dialog-new-conversation').dialog('open');
        });
    </script>
<?php endif; ?>
<?php
$to = $user['User']['username'];
if(!empty($user['User']['name'])) $to .=  ' - '.$user['User']['name'];
?>
<?php echo $this->element('users/modal_send_msg', array('recipient' => $user, 'user_id' => $session->read('Auth.User.id'), 'to' => $to)); ?>
<?php echo $this->element('users/modal_activity'); ?>

<div id="leftcolwapper">
<div class="leftcol">
	<div class="leftcolcontent">
			<div class="userstart">
				<?php
				$link_data = array();

				$link_data['url'] = array('controller' => 'users', 'action' => 'view', 'username' => strtolower($user['User']['username']));
                $extra = ($user['User']['id'] == $session->read('Auth.User.id'))? 'me' : '';
				$link_data['custom'] = array('class' => 'user-image '.$extra, 'alt' => $this->MzText->getUserName($user['User']), 'rel' => $this->MzText->getSubscribeUrl(), 'id' => $user['User']['id'], 'link' => $this->MzHtml->url(array('controller' => 'users', 'action' => 'view', 'username' => strtolower($user['User']['username']))));

				echo $image->render($user['User'], 185, 185, array("alt" => $user['User']['username']), $link_data); ?>
			</div>

        <h4><?php echo $this->MzText->generateDisplayname($user['User'],false);?></h4>
        <?php if($user['User']['name']):?>
          <p><strong><?php echo $user['User']['username'];?></strong></p>
        <?php endif;?>

        <?php //elements shown when being on actions users-view, posts-view ?>
        <?php if(($this->params['controller'] == 'users' &&  in_array($this->params['action'], array('view','viewSubscriptions'))) || ($this->params['controller'] == 'posts' && $this->params['action'] == 'view') || ($this->params['controller'] == 'topics' && $this->params['action'] == 'delete')):?>
        <?php echo $this->element('users/sidebar/info'); ?>
         <?php endif; ?>
        <?php if(($this->params['controller'] == 'users' &&  in_array($this->params['action'], array('view'))) || ($this->params['controller'] == 'posts' && $this->params['action'] == 'view') || ($this->params['controller'] == 'topics' && $this->params['action'] == 'delete')):?>
        <?php echo $this->element('users/sidebar/buttons'); ?>
        <?php echo $this->element('users/sidebar/topics'); ?>
        <?php echo $this->element('users/sidebar/activity'); ?>


       <?php // echo $this->element('users/sidebar/subscriptions'); ?>

        <?php else:?>

            <?php if(!(($this->params['controller'] == 'papers' && ($this->params['action'] == 'add' || $this->params['action'] == 'edit')) || ($this->params['controller'] == 'conversations' && ($this->params['action'] == 'index' || $this->params['action'] == 'view') || ($this->params['action'] == 'accDelete' || $this->params['action'] == 'accImage' || $this->params['action'] == 'accGeneral' || $this->params['action'] == 'accPrivacy' || $this->params['action'] == 'accAboutMe' || $this->params['action'] == 'accSocial' || $this->params['action'] == 'accInvitations')))):?>
                <?php echo $this->element('users/sidebar/buttons'); ?>
             <?php endif;?>
        <?php endif;?>
        <?php //elements shown when being on actions users-viewSubscriptions ?>
        <?php if($this->params['controller'] == 'users' && $this->params['action'] == 'viewSubscriptions'):?>
				<?php echo $this->element('users/sidebar/subscriptions'); ?>
			<?php endif;?>
			<?php //elements shown when being on actions in users-account settings ?>
			<?php if(($this->params['controller'] == 'conversations' && ($this->params['action'] == 'index' || $this->params['action'] == 'view') || ($this->params['controller'] == 'users' && ($this->params['action'] == 'accDelete' || $this->params['action'] == 'accImage' || $this->params['action'] == 'accGeneral' || $this->params['action'] == 'accPrivacy' || $this->params['action'] == 'accAboutMe' || $this->params['action'] == 'accSocial' || $this->params['action'] == 'accInvitations' || $this->params['action'] == 'deleteProfilePicture'  || $this->params['action'] == 'accRssImport')))): ?>
				<?php echo $this->element('users/sidebar/account_menue', array('user_id' => $user['User']['id'])); ?>
        <?php endif;?>

        <?php if($this->params['controller'] == 'users' && $this->params['action'] == 'view'):?>
            <fieldset>
                <legend><?php __('Share'); ?></legend>
                <?php echo $this->element('global/social/icons', array('url' => $this->Html->url($canonical_for_layout, true))); ?>
            </fieldset>
        <?php endif; ?>

        <?php if(($this->params['controller'] == 'users' && ($this->params['action'] =='view' || ($this->params['action'] =='viewSubscriptions' && $session->read('Auth.User.id') != $user['User']['id']) )) || ($this->params['controller'] == 'posts' && $this->params['action'] == 'view' /* && $session->read('Auth.User.id') != $user['User']['id'] */)):?>
        <hr />
            <?php if($this->params['controller'] == 'users' && ($this->params['action'] == 'view' || $this->params['action'] == 'viewSubscriptions') && $session->read('Auth.User.id') != $user['User']['id']): ?>
                <?php echo $this->element('complaints/button', array('model' => 'user', 'complain_target_id' => $user['User']['id'])); ?>
            <?php endif; ?>
            <?php if(($this->params['controller'] == 'posts' && $this->params['action'] == 'view') || ($this->params['controller'] == 'users' && $this->params['action'] == 'view')): ?>

                <?php echo $this->Html->link('<span class="icon rss-icon"></span>'.__('RSS-Feed', true),array('controller' => 'users', 'action' => 'feed', 'username' => strtolower($user['User']['username']) ,'url' => array('ext' => 'rss')), array('class'  => 'btn gray','target'  => '_blank', 'rel' => 'nofollow', 'escape' => false));?>
                 <?php if($this->params['controller'] == 'posts'): ?>
                    <a href="#" rel='nofollow' class="btn gray print" onclick="window.print();" id="21"><span></span><?php __('Print'); ?></a>
                    <?php if($session->read('Auth.User.id') != $user['User']['id']):?>
                        <?php echo $this->element('complaints/button', array('model' => 'post', 'complain_target_id' => $post['Post']['id'])); ?>
                    <?php else: ?>
                        <?php echo $this->element('complaints/script'); //for complain in comments not "my post" ?>
                    <?php endif; ?>
                    
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>

    </div><!-- /.leftcolcontent -->
		</div><!-- /.leftcol -->
		
</div><!-- / #leftcolwapper -->