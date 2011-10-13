<?php header('Content-type: application/json'); ?>
<?php $this->log(json_decode($files, true)); ?>
<?php echo ($files); ?>