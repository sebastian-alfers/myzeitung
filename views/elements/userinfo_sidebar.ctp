				<div id="leftcolwapper">
				<div class="leftcol">
					<div class="leftcolcontent">
							<div class="userstart">
								<?php echo $this->Html->image($image->resize($user['User']['image'], 185, 185, true), array("class" => "userimage", "alt" => $user['User']['username']."-image",));?>
								<a class="btn" href=""><span>+</span>Abonnieren</a>
							</div>
							<h4><?php echo $user['User']['username'];?></h4>
							<?php if($user['User']['firstname'] or $user['User']['name']):?>
							<p><strong><?php echo __('Name:'); ?></strong><?php echo $user['User']['firstname'].' '.$user['User']['name'];?></p>
							<?php endif;?>
							<p><strong><?php echo __('Joined:'); ?></strong><?php echo $this->Time->timeAgoInWords($user['User']['created'], array('end' => '+1 Year'));?></p>
							<p><strong><?php echo __('About me:')?></strong> Lorem ipsum dolor sit amet, consetet. m voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea</p>
							<p class="user-url"><strong>URL: </strong><a href="">www.4bai.de</a></p>
							<hr />

							<?php if(count($user['Topic']) > 0): ?>
							<h6><?php echo __('Filter by Topic', true);?></h6>
							<ul>
									<li><?php echo $this->Html->link(__('All Posts'.' ('.$user['User']['post_count'].')', true), array('controller' => 'users',  'action' => 'view', $user['User']['id'])); ?> </li>
			        			<?php foreach($user['Topic'] as $topic):?>
			        	 			<li><?php echo $this->Html->link($topic['name'].' ('.$topic['post_count'].')', array('controller' => 'users',  'action' => 'view', $user['User']['id'], $topic['id'])); ?> </li>
			      		  		<?php endforeach;?>
			      		  	</ul>
							<hr />
			  				  <?php endif; ?>

							<h6><?php echo __('Activity', true);?></h6>
							  <ul>
								<li><?php echo $user['User']['post_count'].' '.__('Posts', true)?></li>
								<li><?php echo $user['User']['posts_user_count'].' '.__('Reposts', true)?></li>
								<li><?php echo $user['User']['comment_count'].' '.__('Comments', true)?></li>
						   		<li><?php echo $user['User']['content_paper_count'].' '.__('Subscribers', true)?></li>
								<li><?php echo $user['User']['subscription_count'].' '.__('Paper subscriptions', true)?></li>
								<li><?php echo $user['User']['paper_count'].' '.__('created Papers', true)?></li>

							</ul>
							<hr />
							<?php if(count($user['Paper'] > 0)):?>
							<h6><?php echo __('Papers by',true).' '.$user['User']['username']?></h6>
							<ul class="newslist">
							<?php foreach($user['Paper'] as $paper):?>
								<li>
								<?php /* image */ echo  $this->Html->link($this->Html->image($image->resize($paper['image'], 35, 35, true)) , array('controller' => 'papers', 'action' => 'view', $paper['id']),array('escape' => false) );?>
							    <?php /* title */ echo $this->Html->link($paper['title'], array('controller' => 'papers', 'action' => 'view', $paper['id']),array('escape' => false));?>
							    </li>
							 <?php endforeach;?>
							</ul>
							<?php endif;?>
						 </div><!-- /.leftcolcontent -->	
						</div><!-- /.leftcol -->
						
						<iframe src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fplatform&amp;width=218&amp;colorscheme=light&amp;show_faces=true&amp;stream=false&amp;header=false&amp;height=268" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:218px; height:268px;"></iframe>
						
				</div><!-- / #leftcolwapper -->