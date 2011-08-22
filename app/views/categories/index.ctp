<?php echo $html->script('paper/view'); ?>
<?php echo $this->element('papers/sidebar', array('paper_belongs_to_user' => true)); ?>
<div id="maincolwrapper">
    <div id="maincol" class="account message-overview">
        <h4 class="account-title message-title"><?php __('Categories'); ?> - <?php echo $paper['Paper']['title']; ?></h4>
    </div>
<ul>
    <?php foreach($paper['Category'] as $cat): ?>
    <li>
        <?php echo $cat['name']; ?> - <a href="/categories/edit/<?php echo $cat['id']; ?>"><?php __('Edit'); ?></a>
    </li>
    <?php endforeach; ?>
</ul>
</div>