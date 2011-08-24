<script type="text/javascript">
<!--
$(document).ready(function() {	
	$("#link_delete_account").click(function(){
		$('#UserAccDeleteForm').submit();
	});	
});
//-->
</script>
<?php echo $this->element('users/sidebar'); ?>

<div id="maincolwrapper"> 
	<div id="maincol" class="account">
		<h2 class="account-title"><?php echo __('Delete Account', true);?></h2>

				<?php echo $this->Form->create('User', array('class' => 'jqtransform', 'inputDefaults' => array('error' => false, 'div' => false)));?>
				<?php echo $this->Form->hidden('id' , array('value' => $user['User']['id']));?>
				<?php echo $this->Form->hidden('username' , array('value' => $user['User']['username']));?>
				<?php echo $this->Form->hidden('image' , array('value' => $user['User']['image']));?>
				<div class="accept">	
					<p><?php  echo $this->Form->input('delete', array('type' => 'checkbox', 'class' => 'textinput','label' => false)); ?><strong><?php echo __('I want to delete my account. All my information, posts, papers and images will be deleted.', true);?></strong></p>
				
					<a class="btn big" id="link_delete_account"><span>-</span><?php echo __('Delete Account', true);?></a>
				</div>
								
				<?php echo $this->Form->end(array('div' => false,'class' => 'hidden')); ?>	
							
	</div><!-- / #maincol -->

</div><!-- / #maincolwrapper -->