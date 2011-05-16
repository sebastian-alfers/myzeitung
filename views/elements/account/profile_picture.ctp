<?php echo $this->Html->script('account/upload'); ?>

<h4 class="account-image"><?php __('My Profile Picture'); ?></h4>

<form id="file_upload"
	action="<?php echo DS.APP_DIR.DS.'accounts/ajxProfileImageProcess'; ?>"
	method="POST" enctype="multipart/form-data"><input
	type="file" name="file"> 
	<span>+</span><?php __('Add Profile Image'); ?>

<input type="hidden" name="hash"
	value="<?php echo $hash; ?>" />
	

</form>

<br /><br /><br /><br />

<div id="files" style="float: left"></div>
<div>
<ul id="profile_img_preview">
</div>
<hr />
<div id="submit_profile_img" style="display:none">
<?php echo $this->Form->create('Account');?>

<?php //wil be filled, right after submitting form
echo $this->Form->hidden('images',array('value' => '')); ?>

<?php echo $this->Form->hidden('hash',array('value' => $hash)); ?>
 <a class="btn" id="add_profile_img_btn"><span>+</span><?php __('Save new profile picture'); ?></a>
</form>
</div>