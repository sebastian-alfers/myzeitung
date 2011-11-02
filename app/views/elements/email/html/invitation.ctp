<?php
    $sender_name = $this->MzText->generateDisplayName($sender['User'], true);

    $sender_link = $this->Html->link($sender_name, $this->Html->url(array('controller' => 'users', 'action' => 'view', 'username' => strtolower($sender['User']['username'])),true), array('style' => 'color:#232424;'));

?>


<p style='color:#232424;'><strong><?php echo sprintf(Invitation::STANDARD_TITLE_DEU,$sender_link);?></strong></p>
<?php /*<p style='color:#232424;'><strong><?php echo sprintf(Invitation::STANDARD_TITLE_ENG,$sender_link);?></strong></p> */ ?>
<?php if(!empty($text)):?>
    <p style='color:#232424;'><?php echo $text;?></p>
<?php else: ?>
     <p style='color:#232424;'><?php echo Invitation::STANDARD_TEXT_DEU;?></p>
    <p style='color:#232424;'><?php echo Invitation::STANDARD_TEXT_ENG;?></p>
<?php endif;?>

<p style='color:#232424;'>
    <strong><?php echo __('Explore myZeitung', true);?></strong><br />
    <?php echo $this->Html->link(__('Browse Posts', true), $this->Html->url(array('controller' => 'posts', 'action' => 'index'), true), array('style' => 'color:#232424;'));?><br />
    <?php echo $this->Html->link(__('Browse Papers', true), $this->Html->url(array('controller' => 'papers', 'action' => 'index'),true), array('style' => 'color:#232424;'));?><br />
    <?php echo $this->Html->link(__('Browse Authors', true), $this->Html->url(array('controller' => 'users', 'action' => 'index'),true), array('style' => 'color:#232424;'));?><br />
</p>