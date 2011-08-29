<?php $paginator = $this->element('global/paginate'); ?>
<div id="maincolwrapper" class="user-view">
    <div id="maincol">
        <?php if($this->params['action'] == 'index'):?>
          <h2><?php echo __('Browse Users', true);?></h2>
        <?php endif;?>
<div class="article-nav">
    <?php echo $paginator;  ?>
</div>

<?php foreach ($users as $index => $user): ?>

    <div class="articlewrapper">
        <div class="article">
            <ul class="iconbar">
                <?php $tipsy_title = sprintf(__n('%d post', '%d posts', $user['User']['post_count'],true), $user['User']['post_count']);?>
                 <li class="articles tt-title" title="<?php echo $tipsy_title;?>"><?php echo $user['User']['post_count']; ?></li>
                <?php $tipsy_title = sprintf(__n('published in %d paper', 'published in %d papers', $user['User']['content_paper_count'],true), $user['User']['content_paper_count']);?>
                <li class="authors tt-title" title="<?php echo $tipsy_title;?>"><?php echo $user['User']['content_paper_count']; ?></li>
            </ul>
            

            <h4><?php echo $this->Html->link($user['User']['username'],array('controller' => 'users', 'action' => 'view','username' =>  strtolower($user['User']['username'])));?></h4>
             <?php $linktext = $this->MzText->truncate($user['User']['name'], 18,array('ending' => '...', 'exact' => true, 'html' => false)); ?>
            <h5><?php echo $this->Html->link($linktext,array('controller' => 'users', 'action' => 'view','username' =>  $user['User']['username']), array('rel' => 'nofollow'));?></h5>

           <?php
            $image_options = array();
            $image_options['url'] = array('controller' => 'users', 'action' => 'view', 'username' => strtolower($user['User']['username']));
            $image_options['additional'] = 'margin-left:14px';

      //      $image_options['rel'] = 'nofollow';

            $image_options['custom']['alt'] = $this->MzText->getUsername($user['User']);
            $image_options['custom']['class'] = 'user-image';
            $image_options['custom']['id'] = $user['User']['id'];
            $image_options['custom']['rel'] = $this->MzText->getSubscribeUrl();
            $image_options['custom']['link'] = $this->MzHtml->url(array('controller' => 'users', 'action' => 'view', 'username' => strtolower($user['User']['username'])));

            echo $image->render($user['User'], 110, 110, array(), $image_options, ImageHelper::USER);
            ?>



        </div><!-- /.article -->
    </div><!-- / .articlewrapper -->


<?php endforeach; ?>


    <div class="article-nav article-nav-bottom">
    <?php echo $paginator; ?>
    </div><!-- / .article-nav -->

    <div>

    </div>


    </div><!-- / #maincol -->

</div><!-- / #maincolwrapper -->
