<div id="maincolwrapper" class="post-view">
    <div id="maincol">
        <?php if($this->params['action'] == 'index'):?>
        <h2><?php echo __('Browse Posts', true);?></h2>
        <?php endif;?>
        <div class="article-nav">
             <?php echo $this->element('global/paginate'); ?>
        </div>

		<?php foreach ($posts as $index => $post):	
		
				$article_reposted_by_user = false;
				$article_belongs_to_user = false;
				if(is_array($post['Post']['reposters']) && in_array($session->read('Auth.User.id'),$post['Post']['reposters'])){
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
								<li class="reposts tt-title" title="<?php echo $post['Post']['posts_user_count'].' '.__('reposts', true);?>"><?php echo $post['Post']['posts_user_count'];?></li>
								<li class="views tt-title" title="<?php echo $post['Post']['view_count'].' '.__('times viewed', true);?>"><?php echo $post['Post']['view_count'];?></li>
								<li class="comments tt-title" title="<?php echo $post['Post']['comment_count'].' '.__('comments', true);?>"><?php echo $post['Post']['comment_count'];?><span>.</span></li>								
							</ul>

                            <?php // post headline
                                $headline = substr($post['Post']['title'],0,50);
                                if(strlen($post['Post']['title']) > 50){
                                    $headline .='...';
                            }
                            ?>
							<h5><?php echo $this->Html->link($headline, array('controller' => 'posts', 'action' => 'view', $post['Post']['id']));?></h5>

							<?php if(isset($post['Post']['image'][0]) && !empty($post['Post']['image'][0])): ?>
							<?php 
							$info = $image->resize($post['Post']['image'][0]['path'], 200, 117, null, true);//return array bacuse of last param -> true							
							?>
							
								<p style="height:117px;overflow:hidden;margin-bottom:25px;">
									
									<?php //echo 'h: ' .$img_info['height'] . '    -   w: ' . $img_info['width']; ?>
									<?php  echo $this->Html->image($info['path'], array('style' => $info['inline'])); ?>
									
								</p>
							<?php //end image rendering ?>
							<?php else:?>
							<?php //not image -> show text preview?>
								<p>
								<?php echo $post['Post']['content_preview'] . ' ... '; echo $this->Html->link(__('read more',true), array('controller' => 'posts', 'action' => 'view', $post['Post']['id']));?>
								</p>
							<?php endif; ?>
							<ul class="footer">

								<li><?php echo $this->Time->timeAgoInWords($post['Post']['created'], array('end' => '+1 Week'));?></li>
								<li><?php echo __("by", true)." "; echo $this->Html->link($post['User']['username'],array('controller' => 'users', 'action' => 'view', $post['Post']['user_id'])); ?> 
									<?php /* start showing (last) reposter: showing the reposter depending on wether the user is in a blog view or a paper */?> 
										<?php if($this->params['controller'] == 'users' && $this->params['action'] == 'view'): ?> 
											<?php /* blog view - controller users action view */ ?> 
											<?php if($post['PostUser']['repost'] == true): ?> 
												<span class="repost-ico"></span><?php echo $this->Html->link($user['User']['username'],array('controller' => 'users', 'action' => 'view', $user['User']['id'])); ?> 
											<?php endif;?> 
										<?php elseif($this->params['controller'] == 'papers' && $this->params['action'] == 'view'):?> 
										<?php /* paper view - controller papers action view */ ?> 
											<?php if(!empty($post['lastReposter']['id'])):?>
												<span class="repost-ico"></span><?php echo $this->Html->link($post['lastReposter']['username'],array('controller' => 'users', 'action' => 'view', $post['lastReposter']['id'])); ?> 
											<?php endif;?>
										<?php endif;?>
									<?php /* END showing last reposter */?>
								</li>
								<li>
								<?php //echo $this->Html->image($post['User']['image'], array("class" => "user-image", "alt" => $post['User']['username']."-image", "url" => array('controller' => 'users', 'action' => 'view', $post['Post']['user_id'])));?>
								<?php 
								$link_data = array();
								$link_data['url'] = array('controller' => 'users', 'action' => 'view', $post['User']['id']);
								$link_data['additional'] = array('class' => 'user-image');
								echo $image->render($post['User'], 50, 50, array("alt" => $post['User']['username']), $link_data);

								//echo $image->userImage($post['User'], 50, 50, array("alt" => $post['User']['username']), $link_data);

								

//								$img_data = $image->getImgPath($post['User']['image']);
//								if(is_array($img_data)){
//
//									//debug($img_data);die();
//									//found img in db
//									$info = $image->resize($img_data['path'], 48, 48, $img_data['size'], true);
//									$img = $this->Html->image($info['path'], array("alt" => $post['User']['username']));
//
//									echo $this->Html->link($img, array('controller' => 'users', 'action' => 'view', $post['User']['id']), array('class' => "user-image", 'escape' => false, 'style' => 'overflow:hidden;height:48px;width:48px;'));
//								}
//								else{
//									//not logged in
//									$path = $image->resize($img_data, 48, 50, null, false);
//									$img = $this->Html->image($path, array("alt" => $post['User']['username']));
//									echo $this->Html->link($img, array('controller' => 'users', 'action' => 'view', $post['User']['id']), array('class' => "user-image", 'escape' => false));
//								}
								?>
														
								</li>
								<?php /* start of options: edit delete if user is logged in, and it is a post from the user itself // repost - undoRepost if it's another user */?>
								<li>						
								<?php if($article_belongs_to_user):?>
									<?php // posts belongs to user - show edit and delete?>
									<?php echo $this->Html->link(__('Edit Post', true), array('controller' => 'posts', 'action' => 'edit', $post['Post']['id']));?>
									&nbsp;&nbsp;
									<?php echo $this->Html->link(__('Delete Post', true), array('controller' => 'posts', 'action' => 'delete', $post['Post']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $post['Post']['id'])); ?>
								<?php elseif($article_reposted_by_user):?>
									<?php // post does not belong to user - user has already reposted post - show undo repost button?>
									<?php echo $this->Html->link(__('Undo Repost', true), array('controller' => 'posts','action' => 'undoRepost', $post['Post']['id']));?>
								<?php else:?>
									<?php // post does not belong to user - not reposted yet - show repost button?>
									<?php echo $this->Html->link(__('Repost', true), array('controller' => 'posts','action' => 'repost', $post['Post']['id']));?>
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

