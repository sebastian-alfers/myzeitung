<?php if(isset($user['Paper']) && is_array($user['Paper']) && (count($user['Paper']) > 0)):?>
			<h6><?php echo __('Top Papers by',true).' '.$user['User']['username']?></h6>
			<ul class="newslist">
			<?php foreach($user['Paper'] as $paper):?>
				<li>
                <?php echo $this->Html->link($image->render($paper, 35, 35, array("alt" => $paper['title']), null, ImageHelper::PAPER).' '.$paper['title'],
                							 array('controller' => 'papers', 'action' => 'view', $paper['id']),array('escape' => false));?>
			    </li>
			 <?php endforeach;?>
			 <?php if($user['User']['paper_count'] > 3):?>
			 	<li>
			 <?php echo __('Show all papers by').' '.$user['User']['username'];?>
			 	</li>
			 <?php endif;?>
			</ul>
<?php endif;?>