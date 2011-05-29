<script type="text/javascript">
<!--
$(document).ready(function() {	
	$("#link_save_changes").click(function(){
		$('#UserAccPrivacyForm').submit();
	});	
});
//-->
</script>
<?php echo $this->element('users/sidebar'); ?>

<div id="maincolwrapper"> 
	<div id="maincol" class="account">
		<h4 class="account-title"><?php echo __('Privacy Settings', true);?></h4>

				<?php echo $this->Form->create('User');?>
				<?php echo $this->Form->hidden('id' , array('value' => $user['User']['id']));?>
				<?php echo $this->Form->hidden('username' , array('value' => $user['User']['username']));?>
				<?php echo $this->Form->hidden('image' , array('value' => $user['User']['image']));?>
				<div class="accept">	
					<p><?php  echo $this->Form->input('allow_comments', array('type' => 'checkbox', 'class' => 'textinput', 'div' => false,'label' => false)); ?><strong><?php echo __('Allow comments on your Articles?', true);?></strong></p>
					<p><?php  echo $this->Form->input('allow_messages', array('type' => 'checkbox', 'class' => 'textinput', 'div' => false,'label' => false)); ?><strong><?php echo __('Allow people to send you messages?', true);?></strong></p>
					<a class="btn big" id="link_save_changes"><span>+</span><?php echo __('Save Changes', true);?></a>
				</div>
								
				<?php echo $this->Form->end(); ?>	
							
	</div><!-- / #maincol -->

</div><!-- / #maincolwrapper -->