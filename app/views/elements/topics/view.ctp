<div class="modal-content">
    <?php echo $this->Form->create('Posts', array('action' => 'repost'));?>

    <?php if(isset($post_id) && !empty($post_id)): ?>
        <?php echo $this->Form->hidden('post_id', array('value' => $post_id)); ?>
    <?php endif; ?>
    <?php echo $this->Form->input('topic_id', array('type' => 'select', 'options' => $topics)); ?>
</div>
    </form>

<div>
    <div class="modal-buttons">
        <ul>
            <li><a href="#" class="btn" onclick="$('#PostsRepostForm').submit();"><span>+</span><?php __('Send Message'); ?></a></li>
            <li><a href="#" class="btn" onclick="$('#dialog-new-conversation').dialog('close');"><span>-</span><?php __('Cancel'); ?></a></li>
		</ul>
    </div>

</div>