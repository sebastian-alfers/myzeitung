<?php echo __("Welcome to myZeitung.de", true);?> <?php echo $recipient['User']['username']."\n\n";?>

<?php echo __('Start using myZeitung', true)."\n";?>
    <?php echo __('Publish a Post', true);?>: <?php echo $this->Html->url(array('controller' => 'posts', 'action' => 'add'),true)."\n";?>
    <?php echo __('Customize your Profile', true);?>: <?php echo $this->Html->url(array('controller' => 'users', 'action' => 'accAboutMe'),true)."\n";?>

<?php echo __('Explore myZeitung', true)."\n";?>
    <?php echo __('Browse Posts', true);?>: <?php echo $this->Html->url(array('controller' => 'posts', 'action' => 'index'), true)."\n";?>
    <?php echo __('Browse Papers', true);?>: <?php echo $this->Html->url(array('controller' => 'papers', 'action' => 'index'),true)."\n";?>
    <?php echo __('Browse Authors', true);?>: <?php echo $this->Html->url(array('controller' => 'users', 'action' => 'index'),true)."\n";?>
