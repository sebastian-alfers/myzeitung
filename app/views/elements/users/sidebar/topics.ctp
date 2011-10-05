
<?php
$user_is_owner = $user['User']['id'] == $this->Session->read('Auth.User.id');
if($user_is_owner){
    //we can use the same popup as for categories
    echo $this->element('topics/modal_edit', array('user_id' => $user['User']['id']));
}
?>
<?php if(count($user['Topic']) > 0): ?>
<fieldset>
<legend><?php echo __('Show Posts by Topic', true);?></legend>

<ul id="topics-content">
    <li>
    <?php //show only links for not selected items when being in blog overview?>
    <?php if(($this->params['controller'] == 'users' && isset($this->params['pass'][1])) || $this->params['controller'] != 'users'):?>
        <?php /* no topic selected */ echo $this->Html->link(sprintf(__('All Posts (%s)',true),$this->MzNumber->counterToReadableSize($user['User']['post_count'])), array('controller' => 'users',  'action' => 'view', 'username' => strtolower($user['User']['username']))); ?>
    <?php else:?>
        <i><?php /* topic selected - show link*/ echo sprintf(__('All Posts (%s)',true),$this->MzNumber->counterToReadableSize($user['User']['post_count']));?></i>
    <?php endif;?> </li>
        <?php foreach($user['Topic'] as $topic):?>
    <li class="topic">
    <?php  if(($this->params['controller'] == 'users' && isset($this->params['pass'][1]) && $this->params['pass'][1] != $topic['id']) || $this->params['controller'] != 'users' || !isset($this->params['pass'][1])):?>
            <?php /* this topic is not selected - show link */ echo $this->Html->link($topic['name'].' ('.$this->MzNumber->counterToReadableSize($topic['post_count']).')', array('controller' => 'users',  'action' => 'view', 'username' => strtolower($user['User']['username']), $topic['id'])); ?>
        <?php else:?>
            <i><?php  /* this topic is selected - show text*/ echo $topic['name'].' ('.$this->MzNumber->counterToReadableSize($topic['post_count']).')'?></i>
        <?php endif;?>
        <?php if($user_is_owner): ?>
            <span class="edit-icon" topic-id="<?php echo $topic['id']; ?>"></span>
        <?php endif; ?>
        </li>
        <?php endforeach;?>
    </ul>
</fieldset>
      <?php endif; ?>