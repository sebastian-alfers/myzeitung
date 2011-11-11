<div class="rssImportLogs index">
	<h2><?php __('Rss Import Logs');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('duration');?></th>
			<th><?php echo $this->Paginator->sort('rss_feed_id');?></th>
			<th><?php echo $this->Paginator->sort('posts_created');?></th>
			<th><?php echo $this->Paginator->sort('posts_not_created');?></th>
			<th><?php echo $this->Paginator->sort('rss_feeds_items_not_created');?></th>
			<th><?php echo $this->Paginator->sort('rss_items_not_created');?></th>
			<th><?php echo $this->Paginator->sort('rss_items_contents_not_created');?></th>
			<th><?php echo $this->Paginator->sort('category_paper_posts_created');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($rssImportLogs as $rssImportLog):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $rssImportLog['RssImportLog']['id']; ?>&nbsp;</td>
		<td><?php echo $rssImportLog['RssImportLog']['duration']; ?> <?php echo __('Seconds'); ?></td>
		<td>
            <?php if(isset($rssImportLog['RssFeed']['url'])): ?>
			    <?php echo $rssImportLog['RssFeed']['url']; ?>
            <?php endif; ?>
		</td>
		<td><?php echo $rssImportLog['RssImportLog']['posts_created']; ?>&nbsp;</td>
		<td><?php echo $rssImportLog['RssImportLog']['posts_not_created']; ?>&nbsp;</td>
		<td><?php echo $rssImportLog['RssImportLog']['rss_feeds_items_not_created']; ?>&nbsp;</td>
		<td><?php echo $rssImportLog['RssImportLog']['rss_items_not_created']; ?>&nbsp;</td>
		<td><?php echo $rssImportLog['RssImportLog']['rss_items_contents_not_created']; ?>&nbsp;</td>
		<td><?php echo $rssImportLog['RssImportLog']['category_paper_posts_created']; ?>&nbsp;</td>
		<td><?php echo $rssImportLog['RssImportLog']['created']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $rssImportLog['RssImportLog']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
