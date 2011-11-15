<div class="rssImportLogs form">
<?php echo $this->Form->create('RssImportLog');?>
	<fieldset>
		<legend><?php __('Admin Add Rss Import Log'); ?></legend>
	<?php
		echo $this->Form->input('log');
		echo $this->Form->input('duration');
		echo $this->Form->input('rss_feed_id');
		echo $this->Form->input('posts_created');
		echo $this->Form->input('posts_not_created');
		echo $this->Form->input('rss_feeds_item_created');
		echo $this->Form->input('rss_feeds_item_not_created');
		echo $this->Form->input('rss_item_created');
		echo $this->Form->input('rss_item_not_created');
		echo $this->Form->input('rss_item_content_created');
		echo $this->Form->input('rss_item_content_not_created');
		echo $this->Form->input('category_paper_posts_created');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Rss Import Logs', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Rss Feeds', true), array('controller' => 'rss_feeds', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Rss Feed', true), array('controller' => 'rss_feeds', 'action' => 'add')); ?> </li>
	</ul>
</div>