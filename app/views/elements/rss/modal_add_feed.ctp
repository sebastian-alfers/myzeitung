<div id="add-feed" title="<?php __('Import Feed'); ?>" style="display:none" class="modal-content">
	 <div class="modal-content">


    <?php echo $this->Form->create(null, array('url' => array('controller' => 'Rss', 'action' => 'addFeedForUser'), 'class' => 'jqtransform',  'inputDefaults' => array('error' => false, 'div' => false)));?>
         <?php  echo $this->Form->input('feed_url', array('type' => 'text', 'class' => 'textinput', 'label' => __('Feed Url', true))); ?>
    </form>

	</div>
    <div class="modal-buttons">
        <ul>
            <li><a href="#" class="btn" id="submit-add-form"><span>+</span><?php __('Import Feed'); ?></a></li>
            <li><a href="#" class="btn" onclick="$('#delete-feed-topic').dialog('close');"><span>-</span><?php __('Cancel'); ?></a></li>
		</ul>
    </div>
</div>