<?php echo $this->element('users/index/sidebar'); ?>
<?php echo $this->element('users/navigator'); ?>
<?php
$metaDesc = __('Browse this and many other interesting papers on myZeitung.de - or create your own paper!',true);
    $this->set('meta_desc_for_layout', $metaDesc);
?>