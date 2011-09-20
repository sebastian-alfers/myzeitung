<div id="dialog-upload" title="<?php __($title); ?>" style="display:none;">
    <div class="modal-content">
        <h4><?php echo __('Profile picture', true);?></h4>
        <form id="file_upload"
                    action="<?php echo FULL_BASE_URL.DS.'ajax/uploadPicture.json'; ?>"
                    method="POST" enctype="multipart/form-data"><input
                    type="file" name="file">
                    <span>+</span><?php __('Choose image'); ?>
                <input type="hidden" name="hash"
                    value="<?php echo $hash; ?>" />
        </form>

        <?php if($this->Session->read('Auth.User.image')):  ?>
            <?php if($model == 'User'): ?>
                <ul><li><a class="btn gray delete-profile-picture" href="#" id=""><span>-</span><?php __('Delete Current Profile Picture'); ?></a></li></ul>
            <?php endif; ?>
            <?php if($model == 'Paper'): ?>
                <ul><li><a class="btn gray delete-paper-picture" href="#" id="<?php echo $model_id; ?>"><span>-</span><?php __('Delete Current Paper Picture'); ?></a></li></ul>
            <?php endif; ?>
        <?php endif; ?>
        <br /><br />
        <div id="files" style="float: left"></div>
        <ul id="new_img_preview"></ul>
	</div>
    <div class="modal-buttons">
        <div id="submit_new_image" style="display:none">
            <?php echo $this->Form->create($model, array('controller' => $submit['controller'], 'action' => $submit['action'], 'id' => 'NewImageForm')); ?>

                <input type="hidden" value="" id="new_image" name="data[<?php echo $model; ?>][new_image]" />
                <?php echo $this->Form->hidden('id' , array('value' => $model_id));?>
                <?php //wil be filled, right after submitting form
                    echo $this->Form->hidden('images',array('value' => '')); ?>

                <?php echo $this->Form->hidden('hash',array('value' => $hash)); ?>
            <br /><br />
            <hr />
                <ul>
                    <li><a class="btn" id="submit_new_image_btn"><span>+</span><?php __('Save Picture');?></a></li>
                    <li><a href="#" class="btn" onclick="$('#dialog-upload').dialog('close');"><span>-</span><?php __('Cancel'); ?></a></li>
                </ul>
            </form>
        </div>

    </div>
</div>