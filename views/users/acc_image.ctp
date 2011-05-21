<?php echo $this->element('users/sidebar'); ?>
				
<div id="maincolwrapper"> 
	<div id="maincol" class="account">
		<?php echo $this->Html->script('user/upload'); ?>
		<h4 class="account-title"><?php echo __('Profile picture', true);?></h4>
				
							<form id="file_upload"
					action="<?php echo DS.APP_DIR.DS.'users/ajxProfileImageProcess'; ?>"
					method="POST" enctype="multipart/form-data"><input
					type="file" name="file"> 
					<span>+</span><?php __('Add Profile Image'); ?>
				
				<input type="hidden" name="hash"
					value="<?php echo $hash; ?>" />
				</form>
				
				<div id="files" style="float: left"></div>
					<ul id="profile_img_preview">
				</div>
				<hr />
				<div id="submit_profile_img" style="display:none">
				<?php echo $this->Form->create('Users');?>
				
				<?php //wil be filled, right after submitting form
				echo $this->Form->hidden('images',array('value' => '')); ?>
				<?php echo $this->Form->hidden('id' , array('value' => $user['User']['id']));?>
				<?php echo $this->Form->hidden('username' , array('value' => $user['User']['username']));?>
				<?php echo $this->Form->hidden('name' , array('value' => $user['User']['name']));?>
				<?php echo $this->Form->hidden('hash',array('value' => $hash)); ?>
				<div class="accept">	
					<a class="btn big" id="add_profile_img_btn"><span>+</span><?php echo __('Save Changes', true);?></a>
				</div>			
				<?php echo $this->Form->end(); ?>	
							
	</div><!-- / #maincol -->

</div><!-- / #maincolwrapper -->				
				
				
			