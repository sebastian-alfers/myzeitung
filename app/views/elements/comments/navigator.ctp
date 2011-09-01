<ul id="comment_list">
	<?php foreach ($comments as $comment):?>

        <?php //*********************
		 $comment_enabled = $comment['Comment']['enabled'];
         $comment_deleted = false;
         $comment_has_children = true;
         if($comment['Comment']['user_id'] == null){
             $comment_deleted = true;
         }
         if(!isset($comment['children']) || count($comment['children']) == 0){
             $comment_has_children = false;
         }
         //********************  ?>

         <?php //show only deleted or dsiabled comments if they have children?>
        <?php if(!(($comment_enabled == false || $comment_deleted) && $comment_has_children == false)): ?>

            <li class="comment" id="<?php echo $comment['Comment']['id']; ?>">
            <?php $current_comment['Comment'] = $comment['Comment'];?>
            <?php $current_comment['User'] = $comment['User'];?>
            <?php $current_comment['Comment']['reply_id'] = $comment['Comment']['id'];?>
            <?php echo $this->element('comments/comment_content', array('current_comment' => $current_comment, 'post_owner' => $post['Post']['user_id'])); ?>
            <?php if(!empty($comment['children'])):?>
                <ul>


                    <?php foreach($comment['children'] as $reply): ?>
                               <?php
                            //*********************
                             $comment_enabled = $reply['Comment']['enabled'];
                             $comment_deleted = false;
                             $comment_has_children = true;
                             if($reply['Comment']['user_id'] == null){
                                 $comment_deleted = true;
                             }
                             if(!isset($reply['children']) || count($reply['children']) == 0){
                                 $comment_has_children = false;
                             }
                             //********************
                            ?>

                         <?php //show only deleted or dsiabled comments if they have children?>
                        <?php if(!(($comment_enabled == false || $comment_deleted) && $comment_has_children == false)): ?>
                            <li class="comment">
                                <?php $current_comment['Comment'] = $reply['Comment'];?>
                                <?php $current_comment['User'] = $reply['User'];?>
                                <?php $current_comment['Comment']['reply_id'] = $reply['Comment']['id'];?>
                            <?php echo $this->element('comments/comment_content', array('current_comment' => $current_comment, 'post_owner' => $post['Post']['user_id'])); ?>
                            <?php if(!empty($reply['children'])):?>
                                <ul>
                                    <?php foreach($reply['children'] as $replyReply): ?>

                                    <?php
                                        //*********************
                                         $comment_enabled = $replyReply['Comment']['enabled'];
                                         $comment_deleted = false;
                                         $comment_has_children = true;
                                         if($replyReply['Comment']['user_id'] == null){
                                             $comment_deleted = true;
                                         }
                                         //********************
                                        ?>

                                        <?php //dont show disabled or deleted comments?>
                                        <?php if(!($comment_enabled == false || $comment_deleted)): ?>
                                            <li class="comment">
                                            <?php $current_comment['Comment'] = $replyReply['Comment'];?>
                                            <?php $current_comment['User'] = $replyReply['User'];?>
                                            <?php $current_comment['Comment']['reply_id'] = $reply['Comment']['id'];?>
                                            <?php echo $this->element('comments/comment_content', array('current_comment' => $current_comment , 'post_owner' => $post['Post']['user_id'])); ?>
                                            </li> <!-- / .comment -->
                                        <?php endif;?>
                                    <?php endforeach;?>
                                </ul>
                            <?php endif;?>
                            </li> <!-- / .comment -->
                        <?php endif;?>
                    <?php endforeach;?>
                </ul>
            <?php endif;?>
            </li> <!-- / .comment -->
            <?php endif;?>
	<?php endforeach;?>
</ul>