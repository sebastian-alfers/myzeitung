<?php // we use the src of tinymce since we changed it in line 9332 ?>
<?php $this->MzJavascript->link('tiny_mce/tiny_mce_src.js'); ?>
<?php $this->MzJavascript->link('global/upload.js'); ?>
<?php $this->MzJavascript->link('post/add_edit.js'); ?>

<?php echo $this->element('global/upload/modal'); ?>

<?php echo $this->element('posts/add_edit_sidebar'); ?>
<div id="maincolwrapper">
	<div id="maincol" class="create-article">
		<?php echo $this->element('posts/add_edit_content'); ?>
	</div> <!-- /.maincol -->					
</div> <!-- /.maincolwrapper -->
