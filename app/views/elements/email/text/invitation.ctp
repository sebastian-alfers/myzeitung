<?php
    $sender_name = $this->MzText->generateDisplayName($sender['User'], true);

    $sender_link = $this->Html->url(array('controller' => 'users', 'action' => 'view', 'username' => strtolower($sender['User']['username'])),true);

    
?>


<?php echo sprintf(Invitation::STANDARD_TITLE_DEU,$sender_name)."\n";?>
<?php echo sprintf(Invitation::STANDARD_TITLE_ENG,$sender_name)."\n\n";?>
<?php if(!empty($text)):?>
<?php echo $text."\n\n";?>
<?php else: ?>
<?php echo Invitation::STANDARD_TEXT_DEU."\n";?>
<?php echo Invitation::STANDARD_TEXT_ENG."\n\n";?>
<?php endif;?>

<?php echo sprintf(__('Use the following link to get directly to the profile of the inviting user: %s',true), $sender_link)."\n"?>

<?php echo __('Explore myZeitung', true)."\n";?>
    <?php echo __('Browse Posts', true);?>: <?php echo $this->Html->url(array('controller' => 'posts', 'action' => 'index'), true)."\n";?>
    <?php echo __('Browse Papers', true);?>: <?php echo $this->Html->url(array('controller' => 'papers', 'action' => 'index'),true)."\n";?>
    <?php echo __('Browse Authors', true);?>: <?php echo $this->Html->url(array('controller' => 'users', 'action' => 'index'),true)."\n";?>
