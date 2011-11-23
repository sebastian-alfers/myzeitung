
<div class="form">
<?php echo $this->Form->create('Rss', array('url' => array('controller' => 'rss', 'action' => 'analyzeFeed')));?>
	<fieldset>
		<legend><?php __('Add Feed-Url to Analyze'); ?></legend>
	<?php
		echo $this->Form->input('feed_url');
        echo $this->Form->input('extend', array('type' => 'checkbox', 'label' => 'Extended View'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>

<?php echo $valid_posts . ' valid posts'; ?>

<?php if(isset($invalid_posts) && !empty($invalid_posts)): ?>
    <?php debug($invalid_posts); ?>
<?php endif; ?>


<?php if(isset($feed_data) && !empty($feed_data)): ?>

    <?php foreach($feed_data as $data): ?>
            <?php // debug($data); die(); ?>
            <table>
           <?php foreach($data as $key => $value): ?>
               <tr>
                  <td><?php echo $key; ?></td>
                  <td><?php echo $value; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        <br /><br />
        <?php endforeach; ?>

<?php endif; ?>