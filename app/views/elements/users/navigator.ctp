<?php $paginator = $this->element('global/paginate'); ?>
<div id="maincolwrapper" class="user-view">
    <div id="maincol">
        <?php if($this->params['action'] == 'index'):?>
          <h2><?php echo __('Browse Authors', true);?></h2>
        <?php endif;?>
<div class="article-nav">
    <?php echo $paginator;  ?>
</div>

<?php foreach ($users as $index => $user): ?>

    <div class="articlewrapper">
        <div class="article">
            <ul class="iconbar">
                <?php $tipsy_title = sprintf(__n('%s post', '%s posts', $user['User']['post_count'],true), $this->MzNumber->counterToReadableSize($user['User']['post_count']));?>
                 <li class="articles tt-title" title="<?php echo $tipsy_title;?>"><?php echo $this->MzNumber->counterToReadableSize($user['User']['post_count']); ?></li>
                <?php $tipsy_title = sprintf(__n('published in %s paper', 'published in %s papers', $user['User']['subscriber_count'],true), $this->MzNumber->counterToReadableSize($user['User']['subscriber_count']));?>
                <li class="authors tt-title" title="<?php echo $tipsy_title;?>"><?php echo $this->MzNumber->counterToReadableSize($user['User']['subscriber_count']); ?></li>
            </ul>


            <?php $linktext = $this->MzText->truncate($this->MzText->generateDisplayname($user['User'],false), 18,array('ending' => '...', 'exact' => true, 'html' => false)); ?>
            <h4><?php echo $this->Html->link($linktext,array('controller' => 'users', 'action' => 'view','username' =>  strtolower($user['User']['username'])));?></h4>
            <h5><?php if(isset($user['User']['name']) && !empty($user['User']['name'])): ?>
                <?php echo $this->Html->link($user['User']['username'],array('controller' => 'users', 'action' => 'view','username' =>  $user['User']['username']), array('rel' => 'nofollow'));?>
            <?php endif; ?></h5>
           <?php
            $image_options = array();
            $image_options['url'] = array('controller' => 'users', 'action' => 'view', 'username' => strtolower($user['User']['username']));
            $image_options['additional'] = 'margin-left:14px';

      //      $image_options['rel'] = 'nofollow';

            $image_options['custom']['alt'] = $this->MzText->getUsername($user['User']);
            $extra = ($user['User']['id'] == $session->read('Auth.User.id'))? 'me' : '';
            $image_options['custom']['class'] = 'user-image '. $extra;
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
