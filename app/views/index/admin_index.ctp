<div class="users index">
	<h2><?php __('Search - solr');?></h2>
        <br />
    <?php if($is_superadmin): ?>
        <ul>
            <li class='big-btn'><?php echo $this->Html->link(__('<span>!!!</span>'.'Clean up PostUser Index', true), array( 'action' => 'cleanUpPostUserIndex', false),array('escape' => false,'class' => 'btn'), __('Are you sure to refresh the PostUser index?', true)); ?></li>
            <li><?php echo __('Deleting entries for non existing posts. disabling entries for disabled posts. refreshing the reposters array of a post.', true); ?></li>
        </ul>
    <br />
        <ul>
            <li class='big-btn'><?php echo $this->Html->link(__('<span>!!!</span>'.'Clean up ContentPaper Index', true), array( 'action' => 'cleanUpContentPaperIndex', false),array('escape' => false,'class' => 'btn'), __('Are you sure to refresh the PostUser index?', true)); ?></li>
            <li><?php echo __('Cleaning up ContentPaper and CategoryPaperPost entries.', true); ?></li>
        </ul>
    <br />
         <ul>
            <li class='big-btn'><?php echo $this->Html->link(__('<span>!!!</span>'.'Refresh Post- and Paper-Routes', true), array( 'action' => 'refreshPostPaperRoutes', false),array('escape' => false,'class' => 'btn'), __('Are you sure to refresh the Routes for Posts and Papers??', true)); ?></li>
            <li><?php echo __('Refresh all routes for Posts and Papers and refreshing solr-entry if necessary.', true); ?></li>
        </ul>
    <br />
    <br />

    <?php endif; ?>

</div>