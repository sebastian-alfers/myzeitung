<div id="dialog-category-edit" title="<?php __('Paper Category'); ?>" style="display:none">
    <div class="modal-content">
	 <p>
        <form id="CategoryFormEdit" method="post" action="/categories/edit" accept-charset="utf-8">
            <div style="display:none;"><input type="hidden" name="_method" value="POST"></div>
            <input type="hidden" name="data[Category][id]" id="CategoryId" value="" class="CategoryId">
            <input type="hidden" name="data[Category][paper_id]" id="PaperId" value="<?php echo $paper_id; ?>">
            <input type="text" name="data[Category][name]" id="category" class="textinput categoryvalue" style="width:330px;" />
        </form>
	</p>
    </div>
    <div class="modal-buttons">
        <p>
            <?php echo $this->Form->create('Category', array('class' => 'jqtransform', 'inputDefaults' => array('error' => false, 'div' => false)));?>
            <?php echo $this->Form->input('delete', array('type' => 'checkbox', 'class' => 'textinput','label' => false, 'class' => 'deletebox')); ?><strong><?php echo __('Delete Category', true);?></strong>
        </form>
        </p>
        <br />
        <ul>
            <li><a href="#" class="btn" onclick="$('#CategoryFormEdit').submit();"><span>+</span><?php __('Save Category'); ?></a></li>
            <li><a href="#" class="btn" onclick="$('#dialog-category-edit').dialog('close');"><span>-</span><?php __('Cancel'); ?></a></li>
		</ul>
    </div>
</div>