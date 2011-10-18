<div id="dialog-upload" title="<?php __('Upload'); ?>" style="display:none;">
    <div id="content-upload">
        <?php $paper_id = (isset($paper_id))? $paper_id : ''; ?>
        <?php echo $this->element('global/upload/form', array('paper_id' => $paper_id)); ?>
    </div>

</div>

