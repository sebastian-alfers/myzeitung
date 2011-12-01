<?php if($session->read('Auth.User.id')):?>
    <?php echo $this->element('comments/form', array('post_owner_id' => $post_owner_id, 'post_id' => $post_id)); ?>
<?php else: ?>
    <?php echo $this->element('comments/login-text'); ?>
<?php endif; ?>