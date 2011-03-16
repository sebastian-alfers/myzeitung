<?php
echo $javascript->link('ckeditor/ckeditor'); 
?>

<div class="posts form">

<?php echo $this->Form->create('Post', array("enctype" => "multipart/form-data"));?> 	
	<fieldset>
 		<legend><?php __('Add Post'); ?></legend>
	<?php
		echo $this->Form->input('topic_id'); 
		echo $this->Html->link(__('New Topic', true), array('controller' => 'topics',  'action' => 'add')); 
		echo $this->Form->input('title');
		//echo $this->Form->input('content');
		echo $cksource->ckeditor('content');
        echo $form->input('image',array("type" => "file"));  		
		echo $this->Form->hidden('user_id',array('value' => $user_id));

	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<?php echo $this->element('navigation'); ?>	
	<h3><?php __('Options'); ?></h3>
	<ul>
	        <li><?php echo $this->Html->link(__('Back', true), array('controller' => 'users',  'action' => 'view', $session->read('Auth.User.id'))); ?> </li>
	</ul>	
</div>
	

	
	
	<hr />
