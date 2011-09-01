<p><?php echo __("Welcome to myZeitung.de", true);?>
   <strong>&nbsp; <?php echo $recipient['User']['username'];?></strong></p>
<p style='color:#232424;'>
    <strong><?php echo __('Start using myZeitung', true);?></strong><br />
  <?php echo $this->Html->link(__('Publish a Post', true), $this->Html->url(array('controller' => 'posts', 'action' => 'add'),true), array('style' => 'color:#232424;'));?><br />
  <?php echo $this->Html->link(__('Customize your Profile', true), $this->Html->url(array('controller' => 'users', 'action' => 'accAboutMe'),true), array('style' => 'color:#232424;'));?><br />
</p>
<p style='color:#232424;'>
    <strong><?php echo __('Explore myZeitung', true);?></strong><br />
    <?php echo $this->Html->link(__('Browse Posts', true), $this->Html->url(array('controller' => 'posts', 'action' => 'index'), true), array('style' => 'color:#232424;'));?><br />
    <?php echo $this->Html->link(__('Browse Papers', true), $this->Html->url(array('controller' => 'papers', 'action' => 'index'),true), array('style' => 'color:#232424;'));?><br />
    <?php echo $this->Html->link(__('Browse Authors', true), $this->Html->url(array('controller' => 'users', 'action' => 'index'),true), array('style' => 'color:#232424;'));?><br />
</p>