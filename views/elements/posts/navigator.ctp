<div id="maincolwrapper"> 
					<div id="maincol">
					
					<div class="article-nav">
						<div class="pagination">
							<?php echo $this->Paginator->prev(__('Previous', true), null, null, array('class' => 'disabled')); ?>
							<?php echo $this->Paginator->numbers(array('separator' => '')); ?>
							<?php echo $this->Paginator->next(__('Next', true), null, null, array('class' => 'disabled')); ?> 
						</div>			
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
								<li class="reposts"><?php echo $post['Post']['posts_user_count'];?></li>
								<li class="views"><?php echo $post['Post']['view_count'];?></li>
								<li class="comments"><?php echo $post['Post']['comment_count'];?><span>.</span></li>								
							</ul>
							
							<h5><?php echo $this->Html->link($post['Post']['title'], array('controller' => 'posts', 'action' => 'view', $post['Post']['id']));?></h5>

							<?php if(isset($post['Post']['image'][0]) && !empty($post['Post']['image'][0])): ?>
							<?php 

							//aspect ratio of div for image preview
							$div_width = 200;
							$div_height = 117;
							$divAspectRatio = 1.7094017094; // -> 200/117;

							//get aspect ratio of post image
							$img_width = $post['Post']['image'][0]['size'][0];
							$img_height = $post['Post']['image'][0]['size'][1];
							$imageAspectRatio =  $img_width/$img_height;

							
							$inline_styles = '';
							if($imageAspectRatio > $divAspectRatio){
								
								//landscape aspect ratio
								$img_resize_info = $image->resize($post['Post']['image'][0]['path'], 332, 117, true, true);//return array bacuse of last param -> true
								$rel_path = $img_resize_info['path']; 
								//if image is x px wider then the div -> move the half of x to left
								$inline_styles = 'margin-left:-'.(($img_resize_info['width'] - $div_width) / 2) . 'px';	
							}
							else{
								//portrait aspect ratio
								$img_resize_info = $image->resize($post['Post']['image'][0]['path'], 200, 300, true, true);//return array bacuse of last param -> true
								$rel_path = $img_resize_info['path']; 
								//if image is x px wider then the div -> move the half of x to left
								$inline_styles = 'margin-top:-'.(($img_resize_info['height'] - $div_height) / 2) . 'px';
							}
							
							//debug($post['Post']['image'][0]);die(); 
							
							?>
							
								<p style="height:117px;overflow:hidden;margin-bottom:25px;">
									
									<?php //echo 'h: ' .$img_info['height'] . '    -   w: ' . $img_info['width']; ?>
									<?php  echo $this->Html->image($rel_path, array('style' => $inline_styles)); ?>
									
								</p>
							<?php //end image rendering ?>
							<?php else:?>
							<?php //not image -> show text preview?>
								<p>
								<?php echo $post['Post']['content_preview'] . ' ... '; echo $this->Html->link(__('read more',true), array('controller' => 'posts', 'action' => 'view', $post['Post']['id']));?>
								</p>
							<?php endif; ?>
							<ul class="footer">

								<li><?php echo $this->Time->timeAgoInWords($post['Post']['created'], array('end' => '+1 Year'));?></li>
								<li><?php echo __("by", true)." "; echo $this->Html->link($post['User']['username'],array('controller' => 'users', 'action' => 'view', $post['Post']['user_id'])); ?> 
									<?php /* start showing (last) reposter: showing the reposter depending on wether the user is in a blog view or a paper */?> 
										<?php if($this->params['controller'] == 'users' && $this->params['action'] == 'view'): ?> 
											<?php /* blog view - controller users action view */ ?> 
											<?php if($post['PostUser']['repost'] == true): ?> 
												<span class="repost-ico"></span><?php echo $this->Html->link($user['User']['username'],array('controller' => 'users', 'action' => 'view', $user['User']['id'])); ?> 
											<?php endif;?> 
										<?php elseif($this->params['controller'] == 'posts' && $this->params['action'] == 'index'):?> 
										<?php /* paper view - controller papers action view */ ?> 
										
										<?php endif;?>
									<?php /* END showing last reposter */?>
								</li>
								<li><?php echo $this->Html->image($post['User']['image'], array("class" => "user-image", "alt" => $post['User']['username']."-image", "url" => array('controller' => 'users', 'action' => 'view', $post['Post']['user_id'])));?></li>
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
						<div class="pagination">
							<?php echo $this->Paginator->prev(__('Previous', true), null, null, array('class' => 'disabled')); ?>
							<?php echo $this->Paginator->numbers(array('separator' => '')); ?>
							<?php echo $this->Paginator->next(__('Next', true), null, null, array('class' => 'disabled')); ?> 
						</div><!-- / .pagination-->
					</div><!-- / .article-nav -->													
					
					</div><!-- / #maincol -->
					
				
				</div><!-- / #maincolwrapper -->	

