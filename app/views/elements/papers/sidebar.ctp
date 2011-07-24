<?php echo $this->element('papers/modal_references'); ?>

<?php
if(!($session->read('Auth.User.id')) || $paper['Paper']['owner_id'] != $session->read('Auth.User.id')){
    $paper_belongs_to_user = false;
}elseif($paper['Paper']['owner_id'] == $session->read('Auth.User.id')){
    $paper_belongs_to_user = true;
}

if($paper_belongs_to_user){
    echo $html->script('global/upload');
    echo $this->element('global/modal_upload',
                         array('title'  => 'upload paper picture',
                               'hash'   => $hash,
                               'model'  => 'Paper',
                               'model_id'=> $paper['Paper']['id'],
                               'submit' => array('controller' => 'papers', 'action' => 'saveImage')));
}//end pape_belongs_to_user

?>

<div id="leftcolwapper">
<div class="leftcol">
    <div class="leftcolcontent">
            <div class="userstart">
                <?php
                $link_data = array();
                $link_data['url'] = array('controller' => 'papers', 'action' => 'view', $paper['Paper']['id']);
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


        <hr />
        <?php ?>
        <ul>
            <?php if($paper_belongs_to_user):?>
            <li><?php echo $this->Html->link('<span>+</span>'.__('New Category', true), array('controller' => 'categories', 'action' => 'add', Category::PARAM_PAPER, $paper['Paper']['id']), array('escape' => false, 'class' => 'btn', ));?></li>
            <li><a class="btn" id="add_image"><span>+</span><?php echo __('Upload Image', true); ?></a></li>
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
            <?php ?>
            

            <h6><?php echo __('Filter by Category', true);?></h6>
            <ul>
                <li><span class="icon icon-userresults show-associations tt-title" id="paper/<?php echo $paper['Paper']['id']?>" title="<?php printf(__n('%1$s person is published in this paper','%1$s persons are published in this paper',$paper['Paper']['content_paper_count'] , true), $paper['Paper']['content_paper_count']); ?>"></span>
                <?php //show only links for not selected items?>
                <?php if(isset($this->params['pass'][1])):?>
                    <?php /* no topic selected */ echo $this->Html->link(__('front page', true) . '('.$paper['Paper']['category_paper_post_count'].')', array('controller' => 'papers',  'action' => 'view', $paper['Paper']['id'])); ?>
                <?php else:?>
                    <i><?php /* topic selected - show link*/ echo __('front page', true) . ' ('.$paper['Paper']['category_paper_post_count'].')';?></i>
                <?php endif;?> </li>
                <?php foreach($paper['Category'] as $category):?>
                <li><span class="icon icon-userresults show-associations tt-title" id="paper/<?php echo $paper['Paper']['id']?>/<?php echo $category['id']?>" title="<?php printf(__n('%1$s person is published this category','%1$s persons are published this category',$category['content_paper_count'] ,true), $category['content_paper_count']); ?>"></span>
                <?php  if((isset($this->params['pass'][1]) && $this->params['pass'][1] != $category['id']) || !isset($this->params['pass'][1])):?>
                    <?php /* this topic is not selected - show link */ echo $this->Html->link($category['name'].' ('.$category['category_paper_post_count'].')', array('controller' => 'papers',  'action' => 'view', $paper['Paper']['id'], $category['id'])); ?>
                <?php else:?>
                    <i><?php  /* this topic is selected - show text*/ echo $category['name']. ' ('.$category['category_paper_post_count'].')'?></i>
                <?php endif;?>
                </li>
                <?php endforeach;?>
            </ul>
            <hr />


            <h6><?php echo __('Activity', true);?></h6>
              <ul>
                 <li><?php echo sprintf(__n('%d Post', '%d Posts', $paper['Paper']['category_paper_post_count'],true), $paper['Paper']['category_paper_post_count']);?></li>
                 <li><?php echo sprintf(__n('%d Subscribed User/Topic', '%d Subscribed Users/Topics', $paper['Paper']['content_paper_count'],true), $paper['Paper']['content_paper_count']);?></li>
            </ul>
            <hr />
            <?php echo $this->element('complaints/button', array('model' => 'paper', 'complain_target_id' => $paper['Paper']['id'])); ?>


                            <?php /*references*/ // echo $this->Html->link('References', array('controller' => 'papers', 'action' => 'references', 'paper/'.$paper['Paper']['id'])); ?>
         </div><!-- /.leftcolcontent -->
        </div><!-- /.leftcol -->


</div><!-- / #leftcolwapper -->