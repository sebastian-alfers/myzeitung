<?php
$has_topics = false;
if($session->read('Auth.User.topic_count') > 0){
    $has_topics = true;
}

if($has_topics){

    echo $this->element('posts/repost_modal_choose_topic');
}
?>

<div id="maincolwrapper" class="post-view">
    <div id="maincol">
        <?php if($this->params['controller'] == 'posts' &&$this->params['action'] == 'index'):?>
        <h2><?php echo __('Browse Posts', true);?></h2>
        <?php endif;?>
        <?php if($this->params['controller'] == 'papers' &&$this->params['action'] == 'view'):?>
                <h2><?php echo $paper['Paper']['title'];?></h2>
        <?php endif;?>

        <div class="article-nav">
             <?php echo $this->element('global/paginate'); ?>
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
                                 <?php $tipsy_title = sprintf(__n('%d repost', '%d reposts', (int)$post['Post']['repost_count'],true), $post['Post']['repost_count']);?>
								<li class="reposts tt-title" title="<?php echo $tipsy_title;?>"><?php echo $post['Post']['repost_count'];?></li>
								 <?php $tipsy_title = sprintf(__n('%d time viewed', '%d times viewed', (int)$post['Post']['view_count'],true), $post['Post']['view_count']);?>
                                <li class="views tt-title" title="<?php echo $tipsy_title;?>"><?php echo $post['Post']['view_count'];?></li>
								 <?php $tipsy_title = sprintf(__n('%d comment', '%d comments', (int)$post['Post']['comment_count'],true), $post['Post']['comment_count']);?>
                                <li class="comments tt-title" title="<?php echo $tipsy_title;?>"><?php echo $post['Post']['comment_count'];?><span>.</span></li>
							</ul>

                                <?php $headline = $this->Text->truncate($post['Post']['title'], 50,array('ending' => '...', 'exact' => false, 'html' => false)); ?>
                                <?php // post headline
                               /* $headline = substr($post['Post']['title'],0,50);
                                if(strlen($post['Post']['title']) > 50){
                                    $headline .='...';
                                    
                            }*/
                            ?>
							<h5><?php echo $this->Html->link($headline, array('controller' => 'posts', 'action' => 'view', $post['Post']['id']));?></h5>
							<?php if(isset($post['Post']['image']) && !empty($post['Post']['image'])):?>
                                <?php $data = unserialize($post['Post']['image']); $data = $data[0]; ?>
                                 <?php if(isset($data['item_type']) && $data['item_type'] == 'video'): ?>
                                    <span class="post video-item">video</span>
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
								<?php echo $this->Text->truncate(strip_tags($post['Post']['content']), 220,array('ending' => '...'.' '.$this->Html->link(__('read more',true), array('controller' => 'posts', 'action' => 'view', $post['Post']['id'])), 'exact' => false, 'html' => true)); ?>
                                </p>
							<?php endif;  ?>
							<ul class="footer">

								<li><?php echo $this->MzTime->timeAgoInWords($post['Post']['created'], array('format' => 'd.m.y  h:m','end' => '+1 Month'));?></li>
                                <?php // shorten the username depending of: post is shown as repost? -> short names? post regular -> longer names?>
                                  <?php if(($this->params['controller'] == 'users' && $this->params['action'] == 'view' && $post['PostUser']['repost'] == true) ||
                                           ($this->params['controller'] == 'papers' && $this->params['action'] == 'view' && !empty($post['lastReposter']['id']))){

                                           $linktext = $this->Text->truncate($post['User']['username'], 7,array('ending' => '...', 'exact' => true, 'html' => false));

                                        //not paper-view or user-view OR not a repost
                                        }else{
                                           $linktext = $this->Text->truncate($post['User']['username'], 12,array('ending' => '...', 'exact' => true, 'html' => false));

                                        }?>
                                <?php $tipsy_name= $post['User']['username'];
                                        if($post['User']['name']){
                                            $tipsy_name = $post['User']['username'].' - '.$post['User']['name'];
                                        }?>
								<li><?php echo __("by", true)." "; echo $this->Html->link($linktext,array('controller' => 'users', 'action' => 'view', $post['Post']['user_id']), array('class' => 'tt-title', 'title' => $tipsy_name)); ?>
									<?php /* start showing (last) reposter: showing the reposter depending on wether the user is in a blog view or a paper */?> 
										<?php if($this->params['controller'] == 'users' && $this->params['action'] == 'view'): ?> 
											<?php /* blog view - controller users action view */ ?> 
											<?php if($post['PostUser']['repost'] == true): ?>
												<span class="repost-ico"></span>
                                                 <?php $tipsy_name= $user['User']['username'];
                                                if($user['User']['name']){
                                                    $tipsy_name = $user['User']['username'].' - '.$user['User']['name'];
                                                }?>
                                                <?php $linktext = $this->Text->truncate($user['User']['username'], 7,array('ending' => '...', 'exact' => true, 'html' => false)); ?>
                                                <?php echo $this->Html->link($linktext,array('controller' => 'users', 'action' => 'view', $user['User']['id']), array('class' => 'tt-title', 'title' => $tipsy_name)); ?>
											<?php endif;?> 
										<?php elseif($this->params['controller'] == 'papers' && $this->params['action'] == 'view'):?> 
										<?php /* paper view - controller papers action view */ ?> 
											<?php if(!empty($post['lastReposter']['id'])):?>
												<span class="repost-ico"></span>
                                                <?php $tipsy_name= $post['lastReposter']['username'];
                                                if($post['lastReposter']['name']){
                                                    $tipsy_name = $post['lastReposter']['username'].' - '.$post['lastReposter']['name'];
                                                }?>
                                                <?php $linktext = $this->Text->truncate($post['lastReposter']['username'], 7,array('ending' => '...', 'exact' => true, 'html' => false)); ?>
                                                <?php echo $this->Html->link($linktext,array('controller' => 'users', 'action' => 'view', $post['lastReposter']['id']),array('class' => 'tt-title', 'title' => $tipsy_name)); ?>
											<?php endif;?>
										<?php endif;?>
									<?php /* END showing last reposter */?>
								</li>
								<li>
								<?php //echo $this->Html->image($post['User']['image'], array("class" => "user-image", "alt" => $post['User']['username']."-image", "url" => array('controller' => 'users', 'action' => 'view', $post['Post']['user_id'])));?>
								<?php 
								$image_options = array();
								$image_options['url'] = array('controller' => 'users', 'action' => 'view', $post['User']['id']);
								$image_options['custom'] = array('class' => 'user-image');
								echo $image->render($post['User'], 50, 50, array("alt" => $post['User']['username']), $image_options);

//								}
								?>
														
								</li>
								<?php /* start of options: edit delete if user is logged in, and it is a post from the user itself // repost - undoRepost if it's another user */?>
								<li>						
								<?php if($article_belongs_to_user):?>
									<?php // posts belongs to user - show edit and delete?>
									<?php echo $this->Html->link(__('Edit Post', true), array('controller' => 'posts', 'action' => 'edit', $post['Post']['id']));?>
									&nbsp;&nbsp;
									<?php echo $this->Html->link(__('Delete Post', true), array('controller' => 'posts', 'action' => 'delete', $post['Post']['id']), null, sprintf(__('Are you sure you want to delete your post: %s?', true), $post['Post']['title'])); ?>
								<?php elseif($article_reposted_by_user):?>
									<?php // post does not belong to user - user has already reposted post - show undo repost button?>
									<?php echo $this->Html->link(__('Undo Repost', true), array('controller' => 'posts','action' => 'undoRepost', $post['Post']['id']));?>
								<?php else:?>
									<?php // post does not belong to user - not reposted yet - show repost button?>
									<?php //echo $this->Html->link(__('Repost', true), array('controller' => 'posts','action' => 'repost', $post['Post']['id']));?>
                                    <?php
                                    //if the user has one or more topics, no href. in this case, the link will be observed and a popup comes
                                    $link = '/posts/repost/'. $post['Post']['id'];
                                    $class = '';
                                    if($has_topics){
                                        $link = '#';
                                        $class = 'class="repost"';
                                    }

                                    ?>

                                    <a href="<?php echo $link; ?>" <?php echo $class; ?> id="<?php echo $post['Post']['id']; ?>"><?php __('Repost'); ?></a>
								<?php endif;?>
								
								</li>
							</ul>							
							</div><!-- /.article -->
						</div><!-- / .articlewrapper -->
		
		<?php endforeach; ?>
						
					
						
				<div class="article-nav article-nav-bottom">
                          <?php echo $this->element('global/paginate'); ?>
					</div><!-- / .article-nav -->												
					
					</div><!-- / #maincol -->
					
				
				</div><!-- / #maincolwrapper -->	

