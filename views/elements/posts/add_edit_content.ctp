<?php echo $html->script('post/add_edit.js'); ?>
<?php echo $this->element('topics/modal_add_topic'); ?>
<?php echo $this->element('posts/modal_add_url'); ?>



<?php //debug($this->data); ?>
<div class="posts form"><?php echo $this->Form->create('Post', array("enctype" => "multipart/form-data"));?>

<p><?php echo $this->Form->input('id'); ?> <?php echo $this->Form->input('topic_id');

echo $this->Form->input('title');
//echo $this->Form->input('content');
echo $cksource->ckeditor('content', array('escape' => false));
//echo $form->input('image',array("type" => "file", 'label' => ''));
echo $this->Form->hidden('user_id',array('value' => $user_id));
echo $this->Form->hidden('hash',array('value' => $hash));

//wil be filled, right after submitting form
echo $this->Form->hidden('images',array('value' => ''));


?></p>

<div id="files" style="float: left"></div>
<?php if($this->params['action'] == 'add'): ?> <a
	class="btn" id="add_post_btn"><span class="reply-icon"></span><?php __('Add Post'); ?></a>
<?php elseif ($this->params['action'] == 'edit'): ?> <a
	class="btn" id="edit_post_btn"><span class="reply-icon"></span><?php __('Edit Post'); ?></a>
<?php endif; ?>
</form>
</div>