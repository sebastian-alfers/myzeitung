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
				if(is_array($post['Post']['reposters'])){
					if(in_array($session->read('Auth.User.id'),$post['Post']['reposters'])){
						$article_reposted_by_user = true;
					}
				}
				if($session->read('Auth.User.id') == $post['Post']['user_id']){
					$article_belongs_to_user = true;
					//just if a user could somehow repost his own post
					$article_reposted_by_user = false;
				}
				
		?>
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
							<p>
							<?php echo substr(strip_tags($post['Post']['content'], null),0); echo $this->Html->link(__('read more',true), array('controller' => 'posts', 'action' => 'view', $post['Post']['id']));?>
							</p>
							<ul>
								<li><?php echo $this->Time->timeAgoInWords($post['Post']['created'], array('end' => '+1 Year'));?></li>
								<li><?php echo __("by", true)." "; echo $this->Html->link($post['User']['username'],array('controller' => 'users', 'action' => 'view', $post['Post']['user_id']));?><span class="repost-ico"></span><a href="">Hans.Meiser</a></li>
								<li><?php echo $this->Html->image($post['User']['image'], array("class" => "user-image", "alt" => $post['User']['username']."-image", "url" => array('controller' => 'users', 'action' => 'view', $post['Post']['user_id'])));?></li>
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

