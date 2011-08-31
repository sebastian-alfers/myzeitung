<div id="dialog-topic" title="<?php __('Add Topic'); ?>" style="display:none" class="modal-content">
	 <div class="modal-content">
        <form id="TopicForm" method="post" action="/topics/edit" accept-charset="utf-8">
            <input type="text" id="new_topic">
        </form>
	</div>
    <div class="modal-buttons">
        <ul>
            <li><a href="#" class="btn" onclick="saveTopic($('#new_topic').val());"><span>+</span><?php __('Save Topic'); ?></a></li>
            <li><a href="#" class="btn" onclick="$('#dialog-topic').dialog('close');"><span>-</span><?php __('Cancel'); ?></a></li>
		</ul>
    </div>
</div>