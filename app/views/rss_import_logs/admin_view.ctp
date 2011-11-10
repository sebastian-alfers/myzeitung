<div class="rssImportLogs view">
<h2><?php  __('Rss Import Log');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $rssImportLog['RssImportLog']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Log'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $rssImportLog['RssImportLog']['log']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Duration'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $rssImportLog['RssImportLog']['duration']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Rss Feed'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($rssImportLog['RssFeed']['id'], array('controller' => 'rss_feeds', 'action' => 'view', $rssImportLog['RssFeed']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Posts Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $rssImportLog['RssImportLog']['posts_created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Posts Not Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $rssImportLog['RssImportLog']['posts_not_created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Rss Feeds Item Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $rssImportLog['RssImportLog']['rss_feeds_item_created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Rss Feeds Item Not Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $rssImportLog['RssImportLog']['rss_feeds_item_not_created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Rss Item Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $rssImportLog['RssImportLog']['rss_item_created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Rss Item Not Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $rssImportLog['RssImportLog']['rss_item_not_created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Rss Item Content Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $rssImportLog['RssImportLog']['rss_item_content_created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Rss Item Content Not Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $rssImportLog['RssImportLog']['rss_item_content_not_created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Category Paper Posts Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $rssImportLog['RssImportLog']['category_paper_posts_created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $rssImportLog['RssImportLog']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $rssImportLog['RssImportLog']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Rss Import Log', true), array('action' => 'edit', $rssImportLog['RssImportLog']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Rss Import Log', true), array('action' => 'delete', $rssImportLog['RssImportLog']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $rssImportLog['RssImportLog']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Rss Import Logs', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Rss Import Log', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Rss Feeds', true), array('controller' => 'rss_feeds', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Rss Feed', true), array('controller' => 'rss_feeds', 'action' => 'add')); ?> </li>
	</ul>
</div>
