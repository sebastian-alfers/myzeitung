<script type="text/javascript">
<!--
$(document).ready(function() {	
	$("#link_save_changes").click(function(){
		$('#UserAccGeneralForm').submit();
	});	
});
//-->
</script>
<?php echo $this->element('users/sidebar'); ?>

<div id="maincolwrapper"> 
	<div id="maincol" class="account">

		<h2 class="account-title"><?php echo __('General Settings', true);?></h2>

				<?php echo $this->Form->create('User',array( 'inputDefaults' => array('error' => false, 'div' => false)));?>
				<?php echo $this->Form->hidden('id' , array('value' => $user['User']['id']));?>
				<?php echo $this->Form->hidden('username' , array('value' => $user['User']['username']));?>
				<?php echo $this->Form->hidden('image' , array('value' => $user['User']['image']));?>
				<div><?php  echo $this->Form->input('email', array('type' => 'text', 'class' => 'textinput', 'label' => __('Email', true))); ?>
				<?php if(!is_null($this->Form->error('User.email'))): ?>
				 	<div class="error-message"><b></b><?php echo $this->Form->error('User.email', array('wrap'=> false));?></div>
				<?php endif; ?>
				</div>
				
				<div><?php  echo $this->Form->input('old_password', array('type' => 'password', 'class' => 'textinput', 'div' => false,'label' => __('Old Password', true))); ?>
				<?php if(!is_null($this->Form->error('User.old_password'))): ?>
					<div class="error-message"><b></b><?php echo $this->Form->error('User.old_password', array('wrap'=> false));?></div>
				<?php endif; ?>
				</div>
				<div><?php  echo $this->Form->input('passwd', array('type' => 'password', 'class' => 'textinput', 'div' => false,'label' => __('New Password', true))); ?>
				<?php if(!is_null($this->Form->error('User.passwd'))): ?>
					<div class="error-message"><b></b><?php echo $this->Form->error('User.passwd', array('wrap'=> false));?></div>
				<?php endif; ?>
				</div>
				<div><?php  echo $this->Form->input('passwd_confirm', array('type' => 'password', 'class' => 'textinput', 'div' => false,'label' => __('Confirm Password', true))); ?>
				<?php if(!is_null($this->Form->error('User.passwd_confirm'))): ?>
					<div class="error-message"><b></b><?php echo $this->Form->error('User.passwd_confirm', array('wrap'=> false));?></div>
				<?php endif; ?>
				</div>			
				<div class="accept">	
					<a class="btn big" id="link_save_changes"><span>+</span><?php echo __('Save Changes', true);?></a>
				</div>
				<div class="accept">
					<p><?php echo $this->Html->link(__('I want to delete my Account', true), array('controller' => 'users', 'action' => 'accDelete'));?></p>
								
				</div>
				<?php echo $this->Form->end(array('div' => false,'class' => 'hidden')); ?>			


	</div><!-- / #maincol -->

</div><!-- / #maincolwrapper -->