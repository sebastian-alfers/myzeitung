<div id="dialog-new-conversation" title="<?php echo __('New Message to ', true) . $to; ?>" style="display:none;">
    <div class="conversation form account">
    <?php echo $this->Form->create('Conversation', array('action' => 'add', 'class' => 'onecol'));?>

        <?php
            echo $this->Form->hidden('user_id',array('value' => $user_id));
            echo $this->Form->hidden('recipients', array('value' => $recipient['User']['id']));
            echo $this->Form->input('title', array('class' => 'textinput form-error'));
            echo $this->Form->input('message', array('type' => 'textarea', 'class' => 'form-error'));

        ?>

        <input type="submit" style="display: none;" />
    </form>
    </div>

</div>


