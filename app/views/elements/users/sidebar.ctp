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
				$link_data['custom'] = array('class' => 'user-image', 'alt' => $this->MzText->getUserName($user['User']), 'rel' => $this->MzText->getSubscribeUrl(), 'id' => $user['User']['id'], 'link' => $this->MzHtml->url(array('controller' => 'users', 'action' => 'view', 'username' => strtolower($user['User']['username']))));

				echo $image->render($user['User'], 185, 185, array("alt" => $user['User']['username']), $link_data); ?>
			</div>

			<h4><?php echo $user['User']['username'];?></h4>
        <?php //elements shown when being on actions users-view, posts-view ?>
        <?php if(($this->params['controller'] == 'users' && $this->params['action'] == 'view') || ($this->params['controller'] == 'posts' && $this->params['action'] == 'view') || ($this->params['controller'] == 'topics' && $this->params['action'] == 'delete')):?>
        <?php echo $this->element('users/sidebar/info'); ?>
        <?php echo $this->element('users/sidebar/buttons'); ?>
        <?php echo $this->element('users/sidebar/topics'); ?>
        <?php echo $this->element('users/sidebar/activity'); ?>
        <strong><?php  echo __('Papers', true);?>:</strong>
				<?php echo $this->element('users/sidebar/subscriptions'); ?>
        <?php else:?>
            <?php if(!(($this->params['action'] == 'accDelete' || $this->params['action'] == 'accImage' || $this->params['action'] == 'accGeneral' || $this->params['action'] == 'accPrivacy' || $this->params['action'] == 'accAboutMe' || $this->params['action'] == 'accSocial' || $this->params['action'] == 'accInvitations'))):?>
                <?php echo $this->element('users/sidebar/buttons'); ?>
             <?php endif;?>
        <?php endif;?>
        <?php //elements shown when being on actions users-viewSubscriptions ?>
        <?php if($this->params['controller'] == 'users' && $this->params['action'] == 'viewSubscriptions'):?>
				<?php echo $this->element('users/sidebar/subscriptions'); ?>
			<?php endif;?>
			<?php //elements shown when being on actions in users-account settings ?>
			<?php if($this->params['controller'] == 'users' && ($this->params['action'] == 'accDelete' || $this->params['action'] == 'accImage' || $this->params['action'] == 'accGeneral' || $this->params['action'] == 'accPrivacy' || $this->params['action'] == 'accAboutMe' || $this->params['action'] == 'accSocial' || $this->params['action'] == 'accInvitations')): ?>
				<?php echo $this->element('users/sidebar/account_menue', array('user_id' => $user['User']['id'])); ?>
			<?php endif;?>


<?php /*?>
	<hr />
		<h6><?php __('Writes for'); ?></h6>
				<ul>
				<?php foreach($wholeUserReferences as $reference): ?>
					<li><?php echo $reference['Paper']['title']?> <?php if($reference['Category']['id'] == '') echo " (direct in paper)" ?></li>
				<?php endforeach; ?>
				</ul>
				<ul>
				<?php foreach($topicReferences as $reference): ?>
					<li><?php echo $reference['Topic']['name']?> (topic) <?php if($reference['Category']['id'] == '') echo " (direct in paper)" ?></li>
				<?php endforeach; ?>
				</ul>
			<?php */?>



        <?php if($this->params['controller'] == 'users' && ($this->params['action'] == 'view' || $this->params['action'] == 'viewSubscriptions') && $session->read('Auth.User.id') != $user['User']['id']): ?>
            <hr />
            <?php echo $this->element('complaints/button', array('model' => 'user', 'complain_target_id' => $user['User']['id'])); ?>
        <?php endif; ?>
        <?php if($this->params['controller'] == 'posts'): ?>
            <hr />
            <a href="#" class="btn print" onclick="window.print();" id="21"><span></span><?php __('Print'); ?></a>
            <?php echo $this->element('complaints/button', array('model' => 'post', 'complain_target_id' => $post['Post']['id'])); ?>
        <?php endif; ?>
		 </div><!-- /.leftcolcontent -->	
		</div><!-- /.leftcol -->
		
</div><!-- / #leftcolwapper -->