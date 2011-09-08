<div id="dialog-invitation" title="<?php __('Invite Other Authors to myZeitung'); ?>" style="display:none">
    <div class="modal-content">
	 <p>
        <form enctype="multipart/form-data" id="InviteAddForm" method="post" action="/invitation/invite" accept-charset="utf-8">
            <ul id="invite-fields">
                <li><input name="data[Invitation][email][]" type="text"/><span></span></li>
            </ul>
            <input type="submit" value="submit" style="display: none;" />
        </form>
	</p>
    </div>
    <div class="modal-buttons">
        <ul>
            <li><a href="#" class="btn" onclick="validateAndSubmitInvitation();"><span>+</span><?php __('Submit Invitation'); ?></a></li>
		</ul>
    </div>
</div>
