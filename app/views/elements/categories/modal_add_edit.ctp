<div id="dialog-category" title="<?php __('Paper Category'); ?>" style="display:none">
	 <p>
        <form id="CategoryForm" method="post" action="/categories/edit" accept-charset="utf-8">
            <div style="display:none;"><input type="hidden" name="_method" value="POST"></div>
            <input type="hidden" name="data[Category][id]" id="CategoryId" value="">
            <input type="hidden" name="data[Category][paper_id]" id="PaperId" value="<?php echo $paper_id; ?>">
            <input type="text" name="data[Category][name]" id="category" class="textinput" />
        </form>
	</p>
</div>