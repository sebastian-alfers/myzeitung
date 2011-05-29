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
		<h4 class="account-title"><?php echo __('General Settings', true);?></h4>

				<?php echo $this->Form->create('User');?>
				<?php echo $this->Form->hidden('id' , array('value' => $user['User']['id']));?>
				<?php echo $this->Form->hidden('username' , array('value' => $user['User']['username']));?>
				<?php echo $this->Form->hidden('image' , array('value' => $user['User']['image']));?>
				<p><?php  echo $this->Form->input('email', array('type' => 'text', 'class' => 'textinput', 'div' => false,'label' => __('Email', true))); ?></p>
				
				<p><?php  echo $this->Form->input('passwd', array('type' => 'password', 'class' => 'textinput', 'div' => false,'label' => __('Password', true))); ?></p>
				<p><?php  echo $this->Form->input('passwd_confirm', array('type' => 'password', 'class' => 'textinput', 'div' => false,'label' => __('Confirm Password', true))); ?></p>			
				<div class="accept">	
					<a class="btn big" id="link_save_changes"><span>+</span><?php echo __('Save Changes', true);?></a>
				</div>
								
				<?php echo $this->Form->end(); ?>	
							
	</div><!-- / #maincol -->

</div><!-- / #maincolwrapper -->