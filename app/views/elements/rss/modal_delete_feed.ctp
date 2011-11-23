<div id="delete-feed" title="<?php __('Delete Feed'); ?>" style="display:none" class="modal-content">
	 <div class="modal-content">
         <?php __('Do you also want to delete all posts, that have been created in the past based on this Feed?'); ?>

    <?php echo $this->Form->create(null, array('url' => array('controller' => 'Rss', 'action' => 'removeFeedForUser'), 'class' => 'jqtransform', 'id' => 'UserAccRssDeleteForm',  'inputDefaults' => array('error' => false, 'div' => false)));?>
         <br />
            <?php echo $this->Form->hidden('feed_id' , array('value' => ''));?>
            <?php echo $this->Form->input('delete', array('type' => 'checkbox', 'class' => 'textinput','label' => false, 'class' => 'deletebox')); ?><strong><?php echo __('Yes, delete the Feeds posts', true); ?></strong>
    </form>

	</div>
    <div class="modal-buttons">
        <ul>
            <li><a href="#" class="btn" id="submit-delete-form"><span>+</span><?php __('Delete Feed'); ?></a></li>
            <li><a href="#" class="btn" onclick="$('#delete-feed-topic').dialog('close');"><span>-</span><?php __('Cancel'); ?></a></li>
		</ul>
    </div>
</div>