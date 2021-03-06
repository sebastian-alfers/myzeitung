<div id="dialog-invitation" title="<?php __('Invite other authors/readers to myZeitung'); ?>" style="display:none">
    <div class="modal-content account">
        <form enctype="multipart/form-data" id="InviteAddForm" method="post" action="/invitations/add" accept-charset="utf-8">
				<div class="optional info-p textarea">
                    <label for="InvitationText"><?php __('Message'); ?></label>
                    <textarea name="data[Invitation][text]" class="textinput" cols="10" rows="4" id="InvitationText"></textarea>
                    <span class="info"><?php echo __('(optional)',true).' '.__('Attach an optional message to your invitation', true); ?></span>
                </div>
                <div class="optional info-p invitationemails">
                    <label for="InvitationEmail"><?php __('Emails'); ?></label>
                    <ul id="invite-fields" id="InvitationEmail">
                        <li><input name="data[Invitation][email][]" type="text" class="textinput" /><span></span></li>
                    </ul>
                </div>
            </form>
    </div>
    <hr />
    <div class="modal-buttons" style="position: relative;">
        <ul>
            <li><a href="#" class="btn" onclick="validateAndSubmitInvitation();"><span>+</span><?php __('Submit Invitation'); ?></a></li>
            <li><a href="#" class="btn" onclick="$('#dialog-invitation').dialog('close');"><span>-</span><?php __('Cancel'); ?></a></li>
		</ul>
    </div>
</div>
