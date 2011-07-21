<?php echo $this->Form->create('Posts', array('action' => 'repost'));?>

<?php if(isset($post_id) && !empty($post_id)): ?>
    <?php echo $this->Form->hidden('post_id', array('value' => $post_id)); ?>
<?php endif; ?>
<?php echo $this->Form->input('topic_id', array('type' => 'select', 'options' => $topics)); ?>
<?php echo $this->Form->end(__('Submit', true));?>