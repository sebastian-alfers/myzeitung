				<div id="leftcolwapper">
				<div class="leftcol">
					<div class="leftcolcontent">
							<div class="userstart">
								<?php echo $this->Html->image($image->resize($user['User']['image'], 185, 185, true), array("class" => "userimage", "alt" => $user['User']['username']."-image",));?>
								<a class="btn" href=""><span>+</span>Abonnieren</a>
							</div>
							<h4><?php echo $user['User']['username'];?></h4>
							<?php if($user['User']['firstname'] or $user['User']['name']):?>
							<p><strong><?php echo __('Name:').' '; ?></strong><?php echo $user['User']['firstname'].' '.$user['User']['name'];?></p>
							<?php endif;?>
							<p><strong><?php echo __('Joined:').' '; ?></strong><?php echo $this->Time->timeAgoInWords($user['User']['created'], array('end' => '+1 Year'));?></p>
							<?php if(!empty($user['User']['description'])):?>
							<p><strong><?php echo __('About me:').' ';?></strong><?php echo strip_tags($user['User']['description'])?></p>
							<?php endif;?>
							<?php if(!empty($user['User']['url'])):?>
							<p class="user-url"><strong>URL: </strong><?php echo $this->Html->link($user['User']['url'])?></p>
							<?php endif;?>
							<hr />

							<?php if(count($user['Topic']) > 0): ?>
							<h6><?php echo __('Filter by Topic', true);?></h6>
							<ul>
								<li>
								<?php //show only links for not selected items?>
								<?php if(isset($this->params['pass'][1])):?>
									<?php /* no topic selected */ echo $this->Html->link(__('All Posts'.' ('.$user['User']['post_count'].')', true), array('controller' => 'users',  'action' => 'view', $user['User']['id'])); ?>
								<?php else:?>
									<i><?php /* topic selected - show link*/ echo __('All Posts'.' ('.$user['User']['post_count'].')', true);?></i>
								<?php endif;?> </li>
			        			<?php foreach($user['Topic'] as $topic):?>
				        		<li>	
				        	    <?php  if((isset($this->params['pass'][1]) && $this->params['pass'][1] != $topic['id']) || !isset($this->params['pass'][1])):?>
			        	 			<?php /* this topic is not selected - show link */ echo $this->Html->link($topic['name'].' ('.$topic['post_count'].')', array('controller' => 'users',  'action' => 'view', $user['User']['id'], $topic['id'])); ?> 
			        	 		<?php else:?>
			        	 			<i><?php  /* this topic is selected - show text*/ echo $topic['name'].' ('.$topic['post_count'].')'?></i>
			        	 		<?php endif;?>
			        	 		</li>
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
							
							<?php if($user['User']['paper_count'] > 0):?>
							<hr />
							<h6><?php echo __('Top Papers by',true).' '.$user['User']['username']?></h6>
							<ul class="newslist">
							<?php foreach($user['Paper'] as $paper):?>
								<li>
								<?php /* image */ echo  $this->Html->link($this->Html->image($image->resize($paper['image'], 35, 35, true)) , array('controller' => 'papers', 'action' => 'view', $paper['id']),array('escape' => false) );?>
							    <?php /* title */ echo $this->Html->link($paper['title'], array('controller' => 'papers', 'action' => 'view', $paper['id']),array('escape' => false));?>
							    </li>
							 <?php endforeach;?>
							 <?php if($user['User']['paper_count'] > 3):?>
							 	<li>
							 <?php echo __('Show all papers by').' '.$user['User']['username'];?>
							 	</li>
							 <?php endif;?>
							</ul>
							
							<?php endif;?>
						 </div><!-- /.leftcolcontent -->	
						</div><!-- /.leftcol -->
						
				</div><!-- / #leftcolwapper -->