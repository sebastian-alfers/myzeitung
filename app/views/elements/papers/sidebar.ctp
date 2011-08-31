<?php echo $this->element('papers/modal_references'); ?>

<?php

if($paper_belongs_to_user){
    echo $this->element('categories/modal_add_edit', array('paper_id' => $paper['Paper']['id']));
    echo $html->script('global/upload');
    echo $this->element('global/modal_upload',
                         array('title'  => 'upload paper picture',
                               'hash'   => $hash,
                               'model'  => 'Paper',
                               'model_id'=> $paper['Paper']['id'],
                               'submit' => array('controller' => 'paper', 'action' => 'saveImage')));
}//end paper_belongs_to_user

?>

<div id="leftcolwapper">
<div class="leftcol">
    <div class="leftcolcontent">
            <div class="userstart">
                <?php
                $link_data = array();
                $link_data['url'] = $paper['Route'][0]['source'];
                $link_data['custom'] = array('class' => 'paper-image');
                echo $image->render($paper['Paper'], 185, 185, array("alt" => $paper['Paper']['title']), $link_data, ImageHelper::PAPER);

                //echo $this->Html->image($image->resize(['image'], 185, 185, null), array("class" => "userimage", "alt" => $paper['Paper']['title']."-image",));?>

			</div>

        <?php /*<h4><?php echo $paper['Paper']['title'];?></h4> */?>
        <?php if(!empty($paper['Paper']['description'])): ?>
            <p><?php echo strip_tags($paper['Paper']['description']);?></p>
        <?php endif;?>


        <?php if(!empty($paper['Paper']['url'])): ?>
            <p class="user-url"><?php echo $this->Html->link($paper['Paper']['url'], $paper['Paper']['url'], array('rel' => 'nofollow', 'target' => '_blank'));?></p>
        <?php endif;?>

        <?php if(!empty($paper['User']['username'])): ?>
               <?php $tipsy_name= $paper['User']['username'];
                if($paper['User']['name']){
                    $tipsy_name = $paper['User']['username'].' - '.$paper['User']['name'];
                }
                 $linktext = $paper['User']['username']; ?>
                

                <p class="user-url"><?php echo __("by", true)." "; echo $this->Html->link($linktext,array('controller' => 'users', 'action' => 'view','username' =>  strtolower($paper['User']['username'])), array('class' => 'tt-title', 'title' => $tipsy_name)); ?></p>
        <?php endif;?>

        <hr />
        <?php ?>
        <ul>
            <?php if($paper_belongs_to_user):?>
              <li> <?php echo $this->Html->link('<span class="icon settings-icon"></span>'.__('Edit Paper', true),array('controller' => 'papers', 'action' => 'edit',$paper['Paper']['id']), array('class'  => 'btn gray', 'escape' => false));?></li>
              <li><a class="btn gray" id="add_image"><span>+</span><?php echo __('Upload Image', true); ?></a></li>
            <li><a href="#" class="btn gray" id="add_category"><span>+</span><?php __('New Category'); ?></a></li>
            <?php endif;?>
            <?php //subscribe-button: if user is NOT logged in  !OR! paper does not belong to user AND is not subscribed yet?>
            <?php if($this->params['controller'] == 'papers' && $this->params['action'] == 'view'):?>
               <?php if($paper_belongs_to_user == false && $paper['Paper']['subscribed'] == false):?>
                   <li><?php echo $this->Html->link('<span>+</span>'.__('Subscribe', true), array('controller' => 'papers', 'action' => 'subscribe', $paper['Paper']['id']), array('escape' => false, 'class' => 'btn', ));?></li>
                <?php endif;?>
                <?php //unsubscribe-button: if user is logged in  and  paper does not belong to user AND paper is subscribed ?>
                <?php if($paper_belongs_to_user == false && $paper['Paper']['subscribed'] == true):?>
                    <li><?php echo $this->Html->link('<span>-</span>'.__('Unsubscribe', true), array('controller' => 'papers', 'action' => 'unsubscribe', $paper['Paper']['id']), array('escape' => false, 'class' => 'btn', ));?></li>
                <?php endif;?>
            <?php endif;?>
        </ul>
            <hr />

        <?php $category_id = null;?>
        <?php if(isset($this->params['category_id'])): $category_id = $this->params['category_id']; endif;?>
        <div id="category-content">
            <h6><?php echo __('Filter by Category', true);?></h6>
               <ul>
                <li><span class="icon icon-userresults show-associations tt-title" id="paper/<?php echo $paper['Paper']['id']?>" title="<?php printf(__n('%1$s person is published in this paper','%1$s persons are published in this paper',$paper['Paper']['frontpage_authors_count'] , true), $paper['Paper']['frontpage_authors_count']); ?>"></span>
                <?php //show only links for not selected items?>
                <?php if($category_id != null):?>
                    <?php /* no topic selected */ echo $this->Html->link(__('front page', true)/* . '('.$paper['Paper']['post_count'].')'*/, $paper['Route'][0]['source']); ?>
                <?php else:?>
                    <i><?php /* topic selected - show link*/ echo __('front page', true)/* . ' ('.$paper['Paper']['post_count'].')'*/;?></i>
                <?php endif;?> </li>
                <?php foreach($paper['Category'] as $category):?>


                <li class="category"><span class="icon icon-userresults show-associations tt-title" id="paper/<?php echo $paper['Paper']['id']?>/<?php echo $category['id']?>" title="<?php printf(__n('%1$s person is published this category','%1$s persons are published this category',$category['author_count'] ,true), $category['author_count']); ?>"></span>

                <?php  if($category_id != $category['id'] || $category_id == null):?>
                    <?php /* this topic is not selected - show link */ echo $this->Html->link($category['name']/*.' ('.$category['post_count'].')'*/, $paper['Route'][0]['source'].'/'.$category['id']); ?>

                <?php else:?>
                    <i><?php  /* this topic is selected - show text*/ echo $category['name']/*. ' ('.$category['post_count'].')'*/?></i>
                <?php endif;?>
                <?php if($paper_belongs_to_user): ?>
                    <span class="edit-icon" id="/categories/index/<?php echo $paper['Paper']['id']?>" category-id="<?php echo $category['id']; ?>"></span>
                <?php endif; ?>
                </li>
                <?php endforeach;?>
            </ul>
        </div>
            <hr />

            <h6><?php echo __('Activity', true);?></h6>
              <ul>
                 <li><?php echo sprintf(__n('%d Post', '%d Posts', $paper['Paper']['post_count'],true), $paper['Paper']['post_count']);?></li>
                 <li><?php echo sprintf(__n('%d Subscribed User/Topic', '%d Subscribed Users/Topics', $paper['Paper']['author_count'],true), $paper['Paper']['author_count']);?></li>
                 <li><?php echo sprintf(__n('%d Subscriber', '%d Subscribers', $paper['Paper']['subscription_count'],true), $paper['Paper']['subscription_count']);?></li>
            </ul>
            <hr />

            <?php echo $this->element('complaints/button', array('model' => 'paper', 'complain_target_id' => $paper['Paper']['id'])); ?>





                            <?php /*references*/ // echo $this->Html->link('References', array('controller' => 'papers', 'action' => 'references', 'paper/'.$paper['Paper']['id'])); ?>
         </div><!-- /.leftcolcontent -->
        </div><!-- /.leftcol -->


</div><!-- / #leftcolwapper -->