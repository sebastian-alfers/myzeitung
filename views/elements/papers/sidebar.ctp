				<div id="leftcolwapper">
				<div class="leftcol">
					<div class="leftcolcontent">
							<div class="userstart">
								<?php echo $this->Html->image($image->resize($paper['Paper']['image'], 185, 185, null), array("class" => "userimage", "alt" => $paper['Paper']['title']."-image",));?>
								<a class="btn" href=""><span>+</span>Abonnieren</a>
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
					
						 </div><!-- /.leftcolcontent -->	
						</div><!-- /.leftcol -->
						
						<iframe src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fplatform&amp;width=218&amp;colorscheme=light&amp;show_faces=true&amp;stream=false&amp;header=false&amp;height=268" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:218px; height:268px;"></iframe>
						
				</div><!-- / #leftcolwapper -->