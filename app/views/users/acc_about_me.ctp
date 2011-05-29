<script type="text/javascript">
<!--
$(document).ready(function() {	
	$("#link_save_changes").click(function(){
		$('#UserAccAboutMeForm').submit();
	});	
});
//-->
</script>
<?php echo $this->element('users/sidebar'); ?>

<div id="maincolwrapper"> 
	<div id="maincol" class="account">
		<h4 class="account-title"><?php echo __('About Me', true);?></h4>

				<?php echo $this->Form->create('User' /*,array('id' => 'account_changes')*/);?>
				<?php echo $this->Form->hidden('id' , array('value' => $user['User']['id']));?>
				<?php echo $this->Form->hidden('username' , array('value' => $user['User']['username']));?>
				<?php echo $this->Form->hidden('image' , array('value' => $user['User']['image']));?>
				<p class="optional info-p"><?php  echo $this->Form->input('name', array('type' => 'text', 'class' => 'textinput', 'div' => false,'label' => __('Name', true))); ?>
					<span class="info"><?php echo __('(optional)', true);?>&nbsp;<?php echo __('If you want to be found by your name', true);?></span>
				</p>
				
				<p class="optional info-p"><?php  echo $this->Form->input('description', array('type' => 'text', 'class' => 'textinput', 'div' => false,'label' => __('About Me', true))); ?>
					<span class="info"><?php echo __('(optional)', true);?>&nbsp;<?php echo __('Tell people something about yourself.', true);?></span>
				</p>
							
				<div class="accept">	
					<a class="btn big" id="link_save_changes"><span>+</span><?php echo __('Save Changes', true);?></a>
				</div>
								
				<?php echo $this->Form->end(); ?>	
							
	</div><!-- / #maincol -->

</div><!-- / #maincolwrapper -->