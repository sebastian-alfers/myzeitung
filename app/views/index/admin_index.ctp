<div class="users index">
	<h2><?php __('Search - solr');?></h2>
        <br />
    <?php if($is_superadmin): ?>
        <ul>
            <li class='big-btn'><?php echo $this->Html->link(__('<span>!!!</span>'.'Clean up PostUser Index', true), array( 'action' => 'cleanUpPostUserIndex', false),array('escape' => false,'class' => 'btn'), __('Are you sure to refresh the PostUser index?', true)); ?></li>
            <li><?php echo __('blabbel schnabbel', true); ?></li>
        </ul>
    <br />
    <br />

    <?php endif; ?>

</div>