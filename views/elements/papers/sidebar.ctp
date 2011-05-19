				<?php 
				if(!($session->read('Auth.User.id')) || $paper['Paper']['owner_id'] != $session->read('Auth.User.id')){
					$paper_belongs_to_user = false;
				}elseif($paper['Paper']['owner_id'] == $session->read('Auth.User.id')){
					$paper_belongs_to_user = true;
				}

				
				?>
				<div id="leftcolwapper">
				<div class="leftcol">
					<div class="leftcolcontent">
							<div class="userstart">
								<?php echo $this->Html->image($image->resize($paper['Paper']['image'], 185, 185, null), array("class" => "userimage", "alt" => $paper['Paper']['title']."-image",));?>
								<?php //subscribe-button: if user is NOT logged in  !OR! paper does not belong to user AND is not subscribed yet?>
								<?php if($paper_belongs_to_user == false && $paper['Paper']['subscribed'] == false):?>
									<?php echo $this->Html->link('<span>+</span>'.__('Subscribe', true), array('controller' => 'papers', 'action' => 'subscribe', $paper['Paper']['id']), array('escape' => false, 'class' => 'btn', ));?>
								<?php endif;?>
								<?php //unsubscribe-button: if user is logged in  and  paper does not belong to user AND paper is subscribed ?>
								<?php if($paper_belongs_to_user == false && $paper['Paper']['subscribed'] == true):?>
									<?php echo $this->Html->link('<span>-</span>'.__('Unsubscribe', true), array('controller' => 'papers', 'action' => 'unsubscribe', $paper['Paper']['id']), array('escape' => false, 'class' => 'btn', ));?>
								<?php endif;?>
							</div>
							<h4><?php echo $paper['Paper']['title'];?></h4>
							<p><strong><?php echo __('Created:').' '; ?></strong><?php echo $this->Time->timeAgoInWords($paper['Paper']['created'], array('end' => '+1 Year'));?></p>
							<?php if(!empty($paper['Paper']['description'])): ?>
							<p><strong><?php echo __('Description:').' ';?></strong> <?php echo strip_tags($paper['Paper']['description']);?></p>
							<?php endif;?>
							<?php if(!empty($paper['Paper']['url'])): ?>
							<p class="user-url"><strong>URL: </strong><?php echo $this->Html->link($paper['Paper']['url']);?></p>
							<?php endif;?>
							<hr />
							<?php if($paper_belongs_to_user):?>
								<?php echo $this->Html->link('<span>+</span>'.__('New Category', true), array('controller' => 'categories', 'action' => 'add', Category::PARAM_PAPER, $paper['Paper']['id']), array('escape' => false, 'class' => 'btn', ));?>
							<?php endif;?>
							<?php if(count($paper['Category']) > 0): ?>
							<h6><?php echo __('Filter by Category', true);?></h6>
							<ul>
								<li>
								<?php //show only links for not selected items?>
								<?php if(isset($this->params['pass'][1])):?>
									<?php /* no topic selected */ echo $this->Html->link(__('All Posts'.' ('.$paper['Paper']['category_paper_post_count'].')', true), array('controller' => 'papers',  'action' => 'view', $paper['Paper']['id'])); ?>
								<?php else:?>
									<i><?php /* topic selected - show link*/ echo __('All Posts'.' ('.$paper['Paper']['category_paper_post_count'].')', true);?></i>
								<?php endif;?> </li>
			        			<?php foreach($paper['Category'] as $category):?>
				        		<li>	
				        	    <?php  if((isset($this->params['pass'][1]) && $this->params['pass'][1] != $category['id']) || !isset($this->params['pass'][1])):?>
			        	 			<?php /* this topic is not selected - show link */ echo $this->Html->link($category['name'].' ('.$category['category_paper_post_count'].')', array('controller' => 'papers',  'action' => 'view', $paper['Paper']['id'], $category['id'])); ?> 
			        	 		<?php else:?>
			        	 			<i><?php  /* this topic is selected - show text*/ echo $category['name'].' ('.$category['category_paper_post_count'].')'?></i>
			        	 		<?php endif;?>
			        	 		</li>
			      		  		<?php endforeach;?>
			      		  	</ul>
							<hr />
			  				  <?php endif; ?>

							<h6><?php echo __('Activity', true);?></h6>
							  <ul>
								<li><?php echo $paper['Paper']['category_paper_post_count'].' '.__('Posts', true)?></li>
								<li><?php echo $paper['Paper']['content_paper_count'].' '.__('Subcribed Users/Topics', true)?></li>
							</ul>
							<hr />
											<?php /*references*/ echo $this->Html->link('References', array('controller' => 'papers', 'action' => 'references', 'paper/'.$paper['Paper']['id'])); ?>
						 </div><!-- /.leftcolcontent -->	
						</div><!-- /.leftcol -->
						
						
				</div><!-- / #leftcolwapper -->