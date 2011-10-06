<?php

$has_topics = false;
if($session->read('Auth.User.topic_count') > 0){
    $has_topics = true;
}

$paginator = $this->element('global/paginate');


if($has_topics){

    echo $this->element('posts/repost_modal_choose_topic');
    ?>
    <script type="text/javascript">
    $(document).ready(function() {
        observeRepost();
    });
    </script>
    <?php
}
?>

<div id="maincolwrapper" class="post-view">
    <div id="maincol">
        <?php if($this->params['controller'] == 'posts' && $this->params['action'] == 'index'):?>
              <h2><?php echo __('Browse Articles', true);?></h2>
        <?php endif;?>
        <?php if($this->params['controller'] == 'papers' && $this->params['action'] == 'view'):?>
                <h2><?php echo $paper['Paper']['title'];?></h2>
        <?php endif;?>
        <?php if($this->params['controller'] == 'users' && $this->params['action'] == 'view'):?>
            <?php
              $post_count = $user['User']['post_count'] - $user['User']['repost_count'];
            ?>
            <h2><?php echo sprintf(__('%1$s Articles',true),$this->MzText->possessive($this->MzText->generateDisplayname($user['User'],false)));?></h2>
        <?php endif;?>

        <div class="article-nav">
             <?php echo $paginator ?>
        </div>

		<?php foreach ($posts as $index => $post):	
		
				$article_reposted_by_user = false;
				$article_belongs_to_user = false;
                if($this->Reposter->UserHasAlreadyRepostedPost($post['Post']['reposters'], $session->read('Auth.User.id'))){
					$article_reposted_by_user = true;
				}
				if($session->read('Auth.User.id') == $post['Post']['user_id']){
					$article_belongs_to_user = true;
					//just if a user could somehow repost his own post
					$article_reposted_by_user = false;
				}?>
						<div class="articlewrapper">
						<?php if($article_reposted_by_user):?>
						   <span class="repost">repost</span>
						   <?php endif;?>
							<div class="article">
							<ul class="iconbar">
							<?php // tt-title -> class for tipsy &&  'title=...' text for typsy'?>
                                 <?php $tipsy_title = sprintf(__n('%s repost', '%s reposts', $post['Post']['repost_count'],true), $this->MzNumber->counterToReadableSize($post['Post']['repost_count']));?>
								<li class="reposts tt-title" title="<?php echo $tipsy_title;?>"><?php echo $this->MzNumber->counterToReadableSize($post['Post']['repost_count']);?></li>
								 <?php $tipsy_title = sprintf(__n('%s time viewed', '%s times viewed', $post['Post']['view_count'],true), $this->MzNumber->counterToReadableSize($post['Post']['view_count']));?>
                                <li class="views tt-title" title="<?php echo $tipsy_title;?>"><?php echo $this->MzNumber->counterToReadableSize($post['Post']['view_count']);?></li>
								 <?php $tipsy_title = sprintf(__n('%s comment', '%s comments', $post['Post']['comment_count'],true), $this->MzNumber->counterToReadableSize($post['Post']['comment_count']));?>
                                <li class="comments tt-title" title="<?php echo $tipsy_title;?>"><?php echo $this->MzNumber->counterToReadableSize($post['Post']['comment_count']);?><span>.</span></li>
							</ul>

                                <?php $headline = $this->MzText->truncate($post['Post']['title'], 55,array('ending' => '...', 'exact' => false, 'html' => false)); ?>
                                <?php // post headline
                               /* $headline = substr($post['Post']['title'],0,50);
                                if(strlen($post['Post']['title']) > 50){
                                    $headline .='...';
                                    
                            }*/
                            /*<h5><?php echo $this->Html->link($headline, array('controller' => 'posts', 'action' => 'view', $post['Post']['id']));?></h5>   */?>

							<h5><?php echo $this->Html->link($headline, $post['Route'][0]['source']);?></h5>
							<?php if(isset($post['Post']['image']) && !empty($post['Post']['image'])):?>
                                <?php $data = unserialize($post['Post']['image']); $data = $data[0]; ?>
                                 <?php if(isset($data['item_type']) && $data['item_type'] == 'video'): ?>
                                    <a href="/posts/view/<?php echo $post['Post']['id']; ?>"><span class="post video-item">video</span></a>
                                 <?php endif; ?>
                                 <?php echo $image->render($post['Post'], 200, 117, array( "alt" => $post['Post']['title']),  array('tag' => 'p', 'additional' => 'margin-bottom:25px;')); ?>

                             <?php /*
                               if(isset($post['Post']['image'][0]) && !empty($post['Post']['image'][0])):
							  $info = $image->resize($post['Post']['image'][0]['path'], 200, 117, null, true);//return array bacuse of last param -> true
							    ?>

								<p style="height:117px;overflow:hidden;margin-bottom:25px;">
									
									<?php //echo 'h: ' .$img_info['height'] . '    -   w: ' . $img_info['width']; ?>
									<?php  echo $this->Html->image($info['path'], array('style' => $info['inline'])); ?>
									
								</p>
							<?php //end image rendering  */?>
							<?php else:?>
							<?php //not image -> show text preview?>
                                <p>
								<?php //echo $post['Post']['content_preview'] . ' ... '; echo $this->Html->link(__('read more',true), array('controller' => 'posts', 'action' => 'view', $post['Post']['id']));?>
								<?php echo $this->MzText->truncate(strip_tags($post['Post']['content']), 220,array('ending' => '...'.' '.$this->Html->link(__('read more',true), $post['Route'][0]['source'],array('rel' => 'nofollow')), 'exact' => false, 'html' => true)); ?>
                                </p>
							<?php endif;  ?>
							<ul class="footer">

                            <?php /**
                             *  The following area just generates the display names of the creator of the post and maybe (if it is a repost) of the reposter.
                             *  a maxLength is defined. and both names will try to use it as efficient as possible.
                             */ ?>
                                <?php $maxLen = 20;
                                    $isRepost = false;
                                    $tipsy_name = $this->MzText->generateDisplayname($post['User'],true);
                                    $display_name = $this->MzText->generateDisplayname($post['User'],false);
                                 // shorten the username depending of: post is shown as repost? -> short names? post regular -> longer names
                                   if(($this->params['controller'] == 'users' && $this->params['action'] == 'view' && $post['PostUser']['repost'] == true) ||
                                           ($this->params['controller'] == 'papers' && $this->params['action'] == 'view' && !empty($post['lastReposter']['id']))){
                                       //get reposters generated names
                                       $isRepost = true;
                                       //repost and blog view
                                       if($this->params['controller'] == 'users' && $this->params['action'] == 'view' && $post['PostUser']['repost'] == true){
                                          $tipsy_name_reposter = $this->MzText->generateDisplayname($user['User'],true);
                                          $display_name_reposter = $this->MzText->generateDisplayname($user['User'],false);
                                          $reposterUsername = $user['User']['username'];
                                       //repost and paper view
                                       }elseif($this->params['controller'] == 'papers' && $this->params['action'] == 'view' && !empty($post['lastReposter']['id'])){
                                           $tipsy_name_reposter = $this->MzText->generateDisplayname($post['lastReposter'],true);
                                          $display_name_reposter = $this->MzText->generateDisplayname($post['lastReposter'],false);
                                           $reposterUsername = $post['lastReposter']['username'];
                                       }
                                       $maxLenCreator = $maxLen / 2;
                                       $maxLenReposter = $maxLenCreator;
                                       $difference = strlen($display_name) - strlen($display_name_reposter);
                                       $this->log('differece:'.$difference);
                                       if($difference > 0){
                                           $dif2 = $maxLenReposter - strlen($display_name_reposter);
                                           $this->log('dif2:'.$dif2);
                                           if($dif2 > 0){
                                               $maxLenCreator += $dif2;
                                               $maxLenReposter -= $dif2;
                                           }
                                       }elseif($difference < 0){
                                            $dif2 = $maxLenCreator - strlen($display_name);
                                           if($dif2 > 0){
                                               $maxLenReposter += $dif2;
                                               $maxLenCreator -= $dif2;
                                           }
                                       }

                                    //not paper-view or user-view OR not a repost
                                     }else{
                                            $maxLenCreator = $maxLen;
                                     }
                                 // generate linktexts with calculated max lenght

                                $linktextCreator = $this->MzText->truncate($display_name, $maxLenCreator,array('ending' => '...', 'exact' => true, 'html' => false));
                                if(isset($display_name_reposter) && !empty($display_name_reposter)){
                                    $linktextReposter = $this->MzText->truncate($display_name_reposter, $maxLenReposter,array('ending' => '...', 'exact' => true, 'html' => false));
                                }
                                ?>
                                <?php



                                ?>
								<li><?php echo $this->Html->link($linktextCreator,array('controller' => 'users', 'action' => 'view', 'username' => strtolower($post['User']['username'])), array('class' => 'tt-title', 'title' => $tipsy_name)); ?>
									<?php /* start showing (last) reposter: showing the reposter depending on wether the user is in a blog view or a paper */?> 
										<?php if(($this->params['controller'] == 'users' && $this->params['action'] == 'view') || ($this->params['controller'] == 'papers' && $this->params['action'] == 'view')): ?>
											<?php /* blog view - controller users action view */ ?> 
											<?php if($isRepost): ?>
												<span class="repost-ico"></span>
                                                 <?php /* $tipsy_name= $user['User']['username'];
                                                if($user['User']['name']){
                                                    $tipsy_name = $user['User']['username'].' - '.$user['User']['name']; 
                                                } */?>

                                                <?php // $linktext = $this->MzText->truncate($user['User']['username'], 7,array('ending' => '...', 'exact' => true, 'html' => false)); ?>

                                                <?php echo $this->Html->link($linktextReposter,array('controller' => 'users', 'action' => 'view', 'username' =>  strtolower($reposterUsername)), array('class' => 'tt-title', 'title' => $tipsy_name_reposter)); ?>

											<?php endif;?> 
										<?php  //elseif($this->params['controller'] == 'papers' && $this->params['action'] == 'view'):?>
										<?php /* paper view - controller papers action view */ ?> 
											<?php /* if(!empty($post['lastReposter']['id'])):?>
												<span class="repost-ico"></span>
                                                <?php $tipsy_name= $post['lastReposter']['username'];
                                                if($post['lastReposter']['name']){
                                                    $tipsy_name = $post['lastReposter']['username'].' - '.$post['lastReposter']['name'];
                                                }
                                            $tipsy_name = $this->MzText->generateDisplayname($post['lastReposter'],true);
                                            $display_name = $this->MzText->generateDisplayname($post['lastReposter'],false);
                                            ?>

                                                <?php $linktext = $this->MzText->truncate($post['lastReposter']['username'], 7,array('ending' => '...', 'exact' => true, 'html' => false)); ?>
                                                <?php echo $this->Html->link($linktext,array('controller' => 'users', 'action' => 'view',  'username' => strtolower($post['lastReposter']['username'])),array('class' => 'tt-title', 'title' => $tipsy_name)); ?>

											<?php endif; */ ?>
										<?php endif;?>
									<?php /* END showing last reposter */?>
								</li>
                                <li><?php echo $this->MzTime->timeAgoInWords($post['Post']['created'], array('format' => 'd.m.y  h:m','end' => '+1 Month'));?></li>
								<li>
								<?php 
								$image_options = array();

								$image_options['url'] = array('controller' => 'users', 'action' => 'view', 'username' => strtolower($post['User']['username']));
                                $extra = ($post['User']['id'] == $session->read('Auth.User.id'))? 'me' : '';
								$image_options['custom'] = array('class' => 'user-image '.$extra, 'rel' => $this->MzText->getSubscribeUrl(), 'link' => $this->MzHtml->url(array('controller' => 'users', 'action' => 'view', 'username' => strtolower($post['User']['username']))), 'id' => $post['User']['id'], 'alt' => $this->MzText->getUsername($post['User']));
                                $image_options['rel'] = 'nofollow';

								echo $image->render($post['User'], 50, 50, array("alt" => $post['User']['username']), $image_options);

//								}
								?>
														
								</li>
								<?php /* start of options: edit delete if user is logged in, and it is a post from the user itself // repost - undoRepost if it's another user */?>
								<li>						
								<?php if($article_belongs_to_user):?>
									<?php // posts belongs to user - show edit and delete?>
									<?php echo $this->Html->link(__('Edit Post', true), array('controller' => 'posts', 'action' => 'edit', $post['Post']['id']),array('rel' => 'nofollow'));?>
									&nbsp;&nbsp;
									<?php /* echo $this->Html->link(__('Delete Post', true),
                                                                 array('controller' => 'posts', 'action' => 'delete', $post['Post']['id']),
                                                                 array('onlick' => "alert('test')"),
                                                                 sprintf(__('Are you sure you want to delete your post: %s?', true),
                                                                 $post['Post']['title']),array('rel' => 'nofollow')); */ ?>
								<?php elseif($article_reposted_by_user):?>
									<?php // post does not belong to user - user has already reposted post - show undo repost button?>
									<?php echo $this->Html->link(__('Undo Repost', true), array('controller' => 'posts','action' => 'undoRepost', $post['Post']['id']),array('rel' => 'nofollow'));?>
								<?php else:?>
									<?php // post does not belong to user - not reposted yet - show repost button
                                    //if the user has one or more topics, no href. in this case, the link will be observed and a popup comes
                                   $link=  $this->Html->url(array('controller' => 'posts','action' => 'repost', $post['Post']['id']));
                                   //$link = '/posts/repost/'. $post['Post']['id'];
                                    $class = '';
                                    if($has_topics){

                                        $link = '#';
                                    $class = 'repost';
                                    }?>
                                    <?php echo $this->Html->link(__('Repost', true), $link, array('id' => $post['Post']['id'], 'class' => $class, 'rel' => 'nofollow'));?>
                                    
                                   

								<?php endif;?>
								
								</li>
							</ul>							
							</div><!-- /.article -->
						</div><!-- / .articlewrapper -->
		
		<?php endforeach; ?>
						
					
						
				<div class="article-nav article-nav-bottom">
                          <?php echo $paginator ?>
					</div><!-- / .article-nav -->												
					
					</div><!-- / #maincol -->
					
				
				</div><!-- / #maincolwrapper -->	

