<div class="users index">
	<h2><?php __('Search - solr');?></h2>
        <br />
    <?php if($is_superadmin): ?>
         <ul>
            <li class='big-btn'><?php echo $this->Html->link(__('<span>?!?</span>'.'Delete complete Index', true), array( 'action' => 'delete', false),array('escape' => false,'class' => 'btn'), __('Are you sure to DELETE the complete search index?', true)); ?></li>
            <li><?php echo __('DELETES COMPLETE search index. usually there is no reason to do that.', true); ?></li>
        </ul>
    <br />
    <br />
        <ul>
            <li class='big-btn'><?php echo $this->Html->link(__('<span>!!!</span>'.'Refresh Posts Index', true), array( 'action' => 'refreshPostsIndex', false),array('escape' => false,'class' => 'btn'), __('Are you sure to refresh the Solr index for all Posts?', true)); ?></li>
            <li><?php echo __('This algorithm will delete all entries that are deleted or disabled in the database. All other entries will be refreshed in the search index', true); ?></li>
        </ul>
    <br />
    <br />
         <ul>
            <li class='big-btn'><?php echo $this->Html->link(__('<span>!!!</span>'.'Refresh Users Index', true), array( 'action' => 'refreshUsersIndex', false),array('escape' => false,'class' => 'btn'), __('Are you sure to refresh the Solr index for all Users?', true)); ?></li>
            <li><?php echo __('This algorithm will delete all entries that are deleted or disabled in the database. All other entries will be refreshed in the search index', true); ?></li>
        </ul>
    <br />
    <br />
         <ul>
            <li class='big-btn'><?php echo $this->Html->link(__('<span>!!!</span>'.'Refresh Papers Index', true), array( 'action' => 'refreshPapersIndex', false),array('escape' => false,'class' => 'btn'), __('Are you sure to refresh the Solr index for all Papers?', true)); ?></li>
            <li><?php echo __('This algorithm will delete all entries that are deleted or disabled in the database. All other entries will be refreshed in the search index', true); ?></li>
        </ul>
    <br />
    <br />
    <?php endif; ?>

</div>