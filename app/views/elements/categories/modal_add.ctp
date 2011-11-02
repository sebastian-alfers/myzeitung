<div id="dialog-category-add" title="<?php __('Paper Category'); ?>" style="display:none">
    <div class="modal-content">
        <form id="CategoryFormAdd" method="post" action="/categories/edit" accept-charset="utf-8">
            <div style="display:none;"><input type="hidden" name="_method" value="POST"></div>
            <input type="hidden" name="data[Category][id]" id="CategoryId" value="" class="CategoryId" />
            <input type="hidden" name="data[Category][paper_id]" id="PaperId" value="<?php echo $paper_id; ?>" />
            <input type="text" name="data[Category][name]" id="category" class="textinput categoryvalue" style="width:330px;"  maxlength="35" />
        </form>
    </div>
    <div class="modal-buttons">
        <ul>
            <li><a href="#" class="btn" id="btn-submit-new-category"><span>+</span><?php __('Save Category'); ?></a></li>
            <li><a href="#" class="btn" onclick="$('#dialog-category-add').dialog('close');"><span>-</span><?php __('Cancel'); ?></a></li>
		</ul>
    </div>
</div>