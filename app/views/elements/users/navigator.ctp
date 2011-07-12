
<div id="maincolwrapper" class="user-view">
<div id="maincol">
     <h2><?php echo __('Browse Authors', true);?></h2>

<div class="article-nav">
    <?php echo $this->element('global/paginate'); ?>
</div>

<?php foreach ($users as $index => $user): ?>

    <div class="articlewrapper">
        <div class="article">
            <ul class="iconbar">
                <?php // tt-title -> class for tipsy &&  'title=...' text for typsy'?>
             <li class="articles tt-title" title="<?php echo $user['User']['post_count'].' '.__('posts', true);?>"><?php echo $user['User']['post_count']; ?></li>
             <li class="authors tt-title" title="writes for <?php echo $user['User']['post_count'].' '.__(' papers', true);?>"><?php echo $user['User']['content_paper_count']; ?></li>
            </ul>
            <h4><?php echo $this->Html->link($user['User']['username'],array('controller' => 'users', 'action' => 'view', $user['User']['id']));?></h4>
            <h5><?php echo $this->Html->link($user['User']['name'],array('controller' => 'users', 'action' => 'view', $user['User']['id']));?></h5>
            <?php echo $this->Html->link(
                                            $image->render($user['User'], 110, 110, array(), array(), ImageHelper::USER),
                                                array('controller' => 'users', 'action' => 'view', $user['User']['id']),
                                                array('escape' => false));?>

        </div><!-- /.article -->
    </div><!-- / .articlewrapper -->


<?php endforeach; ?>


    <div class="article-nav article-nav-bottom">
    <?php echo $this->element('global/paginate'); ?>
    </div><!-- / .article-nav -->

    <div>

    </div>


    </div><!-- / #maincol -->

</div><!-- / #maincolwrapper -->
