<div id="dialog-upload" title="<?php __($title); ?>" style="display:none;">
    <h4><?php echo __('Profile picture', true);?></h4>

    <form id="file_upload"
                action="<?php echo FULL_BASE_URL.DS.'ajax/uploadPicture.json'; ?>"
                method="POST" enctype="multipart/form-data"><input
                type="file" name="file">
                <span>+</span><?php __('Choose image'); ?>

            <input type="hidden" name="hash"
                value="<?php echo $hash; ?>" />
    </form>
    <div id="submit_new_image" style="display:none">
        <?php echo $this->Form->create($model, array('action' => 'saveImage', 'id' => 'NewImageForm')); ?>

            <input type="hidden" value="" id="new_image" name="data[<?php echo $model; ?>][new_image]" />
            <?php echo $this->Form->hidden('id' , array('value' => $paper['Paper']['id']));?>
            <?php echo $this->Form->hidden('hash',array('value' => $hash)); ?>
            <div class="accept">
                <a class="btn big" id="submit_new_image_btn"><span>+</span><?php echo __('Save Changes', true);?></a>
            </div>
        </form>
    </div>
    <div id="files" style="float: left"></div>
        <ul id="new_img_preview"></ul>
    </div>
