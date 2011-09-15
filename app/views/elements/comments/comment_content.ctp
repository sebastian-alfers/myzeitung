<p class="user-info">
		<?php
		//$info = $image->resize(['image'],65, 65, null, true);
		//echo $this->Html->link($this->Html->image($info['path']),array('controller' => 'users', 'action' => 'view', 'username' => $current_comment['User']['username']),array('class' => "user-image", 'style' => $info['inline'], 'escape' => false));



    // show comment only if it is active OR it is blocked or deleted but has children

        $showLinks = true;


        if($current_comment['Comment']['enabled'] == false){
            $current_comment['User'] = array();
            $current_comment['User']['username'] = __('blocked', true);
            $current_comment['User']['image'] = '';
            $current_comment['Comment']['text'] = __('blocked comment',true);
            $showLinks = false;
        }
        if($current_comment['Comment']['user_id'] == null){
            $current_comment['User'] = array();
            $current_comment['User']['username'] = __('deleted', true);
            $current_comment['User']['image'] = '';
            $current_comment['Comment']['text'] = __('deleted comment', true);
            $showLinks = false;
        }

            $link_data = array();
            $link_data['url'] = array('controller' => 'users', 'action' => 'view', 'username' => strtolower($current_comment['User']['username']));
            $link_data['custom'] = array('class' => 'user-image',
                                         'alt' => $this->MzText->getUsername($current_comment['User']));
                                        // 'link' => $this->MzHtml->url(array('controller' => 'users', 'action' => 'view', 'username' => strtolower($current_comment['User']['username']))),
                                       //  'rel' => $this->MzText->getSubscribeUrl(),
                                       //  'id' => $current_comment['User']['id']);
            if($showLinks){
                $link_data['custom']['id'] = $current_comment['User']['id'];
                $link_data['custom']['rel'] = $this->MzText->getSubscribeUrl();
                $link_data['custom']['link'] = $this->MzHtml->url(array('controller' => 'users', 'action' => 'view', 'username' => strtolower($current_comment['User']['username'])));
            }
            echo $image->render($current_comment['User'], 65, 65, array("alt" => $current_comment['User']['username']), $link_data); ?>
            <?php if($showLinks):?>
                <?php echo $this->Html->link($current_comment['User']['username'],array('controller' => 'users', 'action' => 'view', 'username' => strtolower($current_comment['User']['username'])));?>
            <?php else:?>
            <a>  <?php echo $current_comment['User']['username']?></a>
            <?php endif;?>
            <?php echo $this->MzTime->timeAgoInWords($current_comment['Comment']['created'], array('end' => '+1 Year'));?><br />
        </p>
        <?php if($current_comment['User']['id'] == $post['Post']['user_id']):?>
        <p class="content owner">
            <span class="info owner">kommentar</span>
         <?php else:?>
        <p class="content">
            <span class="info">kommentar</span>
        <?php endif;?>

            <?php echo nl2br($current_comment['Comment']['text']); ?>
        </p>

        <?php
            $comment_user_id = $current_comment['Comment']['user_id'];
            $logged_in_user_id = $session->read('Auth.User.id');

            if($comment_user_id == $logged_in_user_id || $logged_in_user_id == $post_owner): ?>
                <?php echo $this->Html->link('<span class="send-icon"></span>'. __('Remove', true), array('controller' => 'comments', 'action' => 'delete', $current_comment['Comment']['id']), array('escape' => false, 'class' => 'btn gray', ));?>
            <?php endif; ?>

            <div id="btn_comment_complain_<?php echo $current_comment['Comment']['id']; ?>" style="display: none;">
                <?php echo $this->element('complaints/link', array('model' => 'comment', 'complain_target_id' => $current_comment['Comment']['id'])); ?>
            </div>
