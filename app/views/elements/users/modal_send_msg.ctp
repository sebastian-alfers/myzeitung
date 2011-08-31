<div id="dialog-new-conversation" title="<?php echo __('New Message to ', true) . $to; ?>" style="display:none;">
    <div class="modal-content">
    <?php echo $this->Form->create('Conversation', array('action' => 'add', 'class' => 'onecol'));?>

        <?php
            echo $this->Form->hidden('user_id',array('value' => $user_id));
            echo $this->Form->hidden('recipients', array('value' => $recipient['User']['id']));
            echo $this->Form->input('title', array('class' => 'textinput modal'));
            echo $this->Form->input('message', array('type' => 'textarea'));

        ?>

        <input type="submit" style="display: none;" />
    </form>
    </div>
    <div class="modal-buttons">
        <ul>
            <li><a href="#" class="btn" onclick="$('#ConversationAddForm').submit();"><span>+</span><?php __('Send Message'); ?></a></li>
            <li><a href="#" class="btn" onclick="$('#dialog-repost-chosse-topic').dialog('close');"><span>-</span><?php __('Cancel'); ?></a></li>
		</ul>
    </div>

</div>


