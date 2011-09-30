<div id="dialog-url" title="<?php __('Add Website / Url as a reference to you post'); ?>" style="display:none" class="modal-content">
	 <div class="modal-content">
         <input type="text" id="url" class="textinput">
          <input id="orig-url" value="" type="hidden">
	</div>
    <div class="modal-buttons">
        <ul>
            <li><a href="#" class="btn" onclick="prcoessUrl($('#url').val());"><span>+</span><?php __('Save'); ?></a></li>
            <li><a href="#" class="btn" onclick="$('#dialog-url').dialog('close');"><span>-</span><?php __('Cancel'); ?></a></li>
		</ul>
    </div>
</div>


	 <p>

	</p> 
	<p>
		<div id="url_content"></div>
	</p>
