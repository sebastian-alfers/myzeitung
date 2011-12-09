<?php echo $this->element('papers/modal_references'); ?>
<?php $this->MzJavascript->link('paper/view'); ?>

<cake:nocache>
<?php

if(!($session->read('Auth.User.id')) || $paper['Paper']['owner_id'] != $session->read('Auth.User.id')){
    $paper_belongs_to_user = false;
}elseif($paper['Paper']['owner_id'] == $session->read('Auth.User.id')){
    $paper_belongs_to_user = true;
}

if($paper_belongs_to_user){
    echo $this->element('categories/modal_edit', array('paper_id' => $paper['Paper']['id']));
    echo $this->element('categories/modal_add', array('paper_id' => $paper['Paper']['id']));

    $this->MzJavascript->link('global/upload');
    $this->MzJavascript->link('paper/edit');
    
    echo $this->element('global/upload/modal', array('paper_id' => $paper['Paper']['id']));


}//end paper_belongs_to_user
?>
</cake:nocache>

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

            <?php $about = strip_tags($paper['Paper']['description']); ?>
            <p class="aboutme"><i><?php echo $about; ?></i>
            </p>
            <?php if(strlen($about) > 120): ?>
                <a href="#" onclick="$('.aboutme-large').html($('.aboutme').html());$('#dialog-aboutme').dialog('open');"><?php echo "... " . __('read more', true); ?></a>
                <div id="dialog-aboutme" title="<?php echo sprintf(__('About %s', true),$paper['Paper']['title']); ?>" style="display:none;">
                    <div class="modal-content aboutme-large">

                    </div>
                </div>
                <script type="text/javascript">
                $( "#dialog-aboutme" ).dialog({
                    resizable: false,
                    height:340,
                    width:400,
                    draggable:false,
                    modal: true,
                    autoOpen: false
                });
                </script>
            <?php endif; ?>

        <?php endif;?>


        <?php if(!empty($paper['Paper']['url'])): ?>
            <p class="user-url"><?php echo $this->Html->link($paper['Paper']['url'], $paper['Paper']['url'], array('rel' => 'nofollow', 'target' => '_blank'));?></p>
        <?php endif;?>

        <?php //if(!empty($paper['User']['username'])): ?>
               <?php $tipsy_name= $this->MzText->generateDisplayname($paper['User'], true); ?>
               <?php $display_name = $this->MzText->generateDisplayname($paper['User'], false); ?>

           <p class="user-url"><?php echo $this->Html->link($display_name,array('controller' => 'users', 'action' => 'view','username' =>  strtolower($paper['User']['username'])), array('escape' => false, 'class' => 'tt-title', 'title' => $tipsy_name)); ?></p>
        <?php // endif;?>

        <?php $category_id = null;?>
        <?php if(isset($this->params['category_id'])): $category_id = $this->params['category_id']; endif;?>
       <?php // <div id="category-content"> ?>
            <fieldset id="category-content">
            <legend><?php echo __('Filter by Category', true);?></legend>
               <ul>
                <li><span class="icon icon-userresults show-associations tt-title" id="paper/<?php echo $paper['Paper']['id']?>" title="<?php printf(__n('%1$s person is published in this paper','%1$s persons are published in this paper',$paper['Paper']['frontpage_authors_count'] , true), $paper['Paper']['frontpage_authors_count']); ?>"></span>
                <?php //show only links for not selected items?>
                <?php if($category_id != null):?>
                    <?php /* no topic selected */ echo $this->Html->link(__('front page', true)/* . '('.$this->MzNumber->counterToReadableSize($paper['Paper']['post_count']).')'*/, $paper['Route'][0]['source']); ?>
                <?php else:?>
                    <i><?php /* topic selected - show link*/ echo __('front page', true)/* . ' ('.$this->MzNumber->counterToReadableSize($paper['Paper']['post_count']).')'*/;?></i>
                <?php endif;?> </li>
                <?php foreach($paper['Category'] as $category):?>


                <li class="category"><span class="icon icon-userresults show-associations tt-title" id="paper/<?php echo $paper['Paper']['id']?>/<?php echo $category['id']?>" title="<?php printf(__n('%1$s person is published this category','%1$s persons are published this category',$category['author_count'] ,true), $category['author_count']); ?>"></span>

                <?php  if($category_id != $category['id'] || $category_id == null):?>
                    <?php /* this topic is not selected - show link */ echo $this->Html->link($category['name']/*.' ('.$this->MzNumber->counterToReadableSize($category['post_count']).')'*/, $paper['Route'][0]['source'].'/'.$category['id']); ?>

                <?php else:?>
                    <i><?php  /* this topic is selected - show text*/ echo $category['name']/*. ' ('.$this->MzNumber->counterToReadableSize($category['post_count']).')'*/?></i>
                <?php endif;?>
                <?php if($paper_belongs_to_user): ?>
                    <span class="edit-icon" id="/categories/index/<?php echo $paper['Paper']['id']?>" category-id="<?php echo $category['id']; ?>"></span>
                <?php endif; ?>
                </li>
                <?php endforeach;?>
            </ul>
            </fieldset>
        <?php // </div> ?>



             <fieldset>
            <legend><?php echo __('Options', true);?></legend>
            <ul>
                <?php if($paper_belongs_to_user):?>
                    <li> <?php echo $this->Html->link('<span class="icon settings-icon"></span>'.__('Edit Paper', true),array('controller' => 'papers', 'action' => 'edit',$paper['Paper']['id']), array('class'  => 'btn gray', 'id' => 'edit-paper-btn', 'escape' => false));?></li>
                    <li><a class="btn gray" id="add_image"><span>+</span><?php echo __('Upload Image', true); ?></a></li>
                    <li><a href="#" class="btn gray" id="add_category"><span>+</span><?php __('New Category'); ?></a></li>
                <?php endif;?>
                <?php //subscribe-button: if user is NOT logged in  !OR! paper does not belong to user AND is not subscribed yet?>
                <?php if($this->params['controller'] == 'papers' && $this->params['action'] == 'view'):?>
                    <?php if($paper_belongs_to_user == false && $paper['Paper']['subscribed'] == false):?>
                        <li><?php echo $this->Html->link('<span>+</span>'.__('Subscribe Paper', true), array('controller' => 'papers', 'action' => 'subscribe', $paper['Paper']['id']), array('escape' => false, 'class' => 'btn', 'id' => 'subscribe-paper' ));?></li>
	 	            <?php endif;?>
	                <?php //unsubscribe-button: if user is logged in  and  paper does not belong to user AND paper is subscribed ?>
                    <?php if($paper_belongs_to_user == false && $paper['Paper']['subscribed'] == true):?>
                        <li><?php echo $this->Html->link('<span>-</span>'.__('Unsubscribe', true), array('controller' => 'papers', 'action' => 'unsubscribe', $paper['Paper']['id']), array('escape' => false, 'class' => 'btn', 'id' => 'unsubscribe-paper' ));?></li>
	 	            <?php endif;?>
                <?php endif;?>
           </ul>
         </fieldset>



            <fieldset>
            <legend><?php echo __('Activity', true);?></legend>
              <ul>
                 <li><?php echo sprintf(__n('%s Post', '%s Posts', $paper['Paper']['post_count'],true), $this->MzNumber->format($paper['Paper']['post_count'],'.'));?></li>
                 <li><span class="show-associations" id="paper/<?php echo $paper['Paper']['id']?>"><a><?php echo sprintf(__n('%s abo. Autor', '%s abo. Autoren', $paper['Paper']['author_count'],true), $this->MzNumber->format($paper['Paper']['author_count'],'.'));?></a></span></li>
                 <li><?php echo sprintf(__n('%s Subscriber', '%s Subscribers', $paper['Paper']['subscription_count'],true), $this->MzNumber->format($paper['Paper']['subscription_count'],'.'));?></li>
            </ul>
          </fieldset>

        <fieldset>
            <legend><?php __('Share'); ?></legend>
            <?php echo $this->element('global/social/icons', array('url' => $this->Html->url($canonical_for_layout, true))); ?>
        </fieldset>

        <hr />
     <?php echo $this->Html->link('<span class="icon rss-icon"></span>'.__('RSS-Feed', true),$paper['Route'][0]['source'].'/feed', array('class'  => 'btn gray', 'target'  => '_blank', 'rel' => 'nofollow', 'escape' => false));?>
     <?php echo $this->element('complaints/button', array('model' => 'paper', 'complain_target_id' => $paper['Paper']['id'])); ?>




                            <?php /*references*/ // echo $this->Html->link('References', array('controller' => 'papers', 'action' => 'references', 'paper/'.$paper['Paper']['id'])); ?>
         </div><!-- /.leftcolcontent -->
        </div><!-- /.leftcol -->


</div><!-- / #leftcolwapper -->