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
		<h2 class="account-title"><?php echo __('Privacy Settings', true);?></h2>

				<?php echo $this->Form->create('User', array('class' => 'jqtransform',  'inputDefaults' => array('error' => false, 'div' => false)));?>
				<?php echo $this->Form->hidden('id' , array('value' => $user['User']['id']));?>
				<div class="accept">
                    <h4><?php echo __('General', true);?></h4>
					<p><?php  echo $this->Form->input('allow_comments', array('type' => 'checkbox', 'class' => 'textinput','label' => false)); ?><strong><?php echo __('Allow comments on my Articles', true);?></strong></p>
					<p><?php  echo $this->Form->input('allow_messages', array('type' => 'checkbox', 'class' => 'textinput','label' => false)); ?><strong><?php echo __('Allow people to send me messages', true);?></strong></p>
                    <h4><?php echo __('I want to receive emails when', true);?></h4>
                    <?php /*
                    <p><?php   echo $this->Form->input('email_invitee_registered', array('type' => 'checkbox', 'class' => 'textinput','label' => false)); ?><strong><?php echo __('email invitee registered ', true);?></strong></p> */?>
                    <p><?php  echo $this->Form->input('email_new_message', array('type' => 'checkbox', 'class' => 'textinput','label' => false)); ?><strong><?php echo __('someone sent me a new message', true);?></strong></p>
                    <p><?php  echo $this->Form->input('email_new_comment', array('type' => 'checkbox', 'class' => 'textinput','label' => false)); ?><strong><?php echo __('someone wrote a new comment on my article', true);?></strong></p>
                    <p><?php  echo $this->Form->input('email_subscription', array('type' => 'checkbox', 'class' => 'textinput','label' => false)); ?><strong><?php echo __('someone subscribed me', true);?></strong></p>
					<a class="btn big" id="link_save_changes"><span>+</span><?php echo __('Save Changes', true);?></a>
				</div>
				<?php echo $this->Form->end(array('div' => false,'class' => 'hidden')); ?>	
							
	</div><!-- / #maincol -->

</div><!-- / #maincolwrapper -->