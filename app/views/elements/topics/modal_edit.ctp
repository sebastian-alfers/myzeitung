<div id="dialog-topic" title="<?php __('Edit Topic'); ?>" style="display:none" class="modal-content">
	 <div class="modal-content">
        <form id="TopicForm" method="post" action="/topics/edit" accept-charset="utf-8">
            <div style="display:none;"><input type="hidden" name="_method" value="POST"></div>
            <input type="hidden" name="data[Topic][id]" id="TopicId" value="">
            <input type="hidden" name="data[Topic][user_id]" id="UserId" value="<?php echo $user_id; ?>">
            <input type="text" name="data[Topic][name]" id="topic" class="textinput" />
        </form>
	</div>
    <div class="modal-buttons">
        <ul>
            <li><a href="#" class="btn" onclick="$('#TopicForm').submit();"><span>+</span><?php __('Save Topic'); ?></a></li>
            <li><a href="#" class="btn" onclick="$('#dialog-topic').dialog('close');"><span>-</span><?php __('Cancel'); ?></a></li>
		</ul>
    </div>
</div>