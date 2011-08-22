<?php if(isset($user['Paper']) && is_array($user['Paper']) && (count($user['Paper']) > 0)):?>
			<h6><?php echo __('Top Papers by',true).' '.$user['User']['username']?></h6>
			<ul class="newslist">
			<?php foreach($user['Paper'] as $paper):?>
				<li>
                <?php
                $container_data['tag'] = 'div';
                $container_data['custom']['float'] = 'left';
                $container_data['custom']['margin-right'] = '15px';

                $img = $image->render($paper, 35, 35, array("alt" => $paper['title']), $container_data, ImageHelper::PAPER);
                echo $this->Html->link($img.' '.$paper['title'],
                            $paper['Route'][0]['source'],array('escape' => false));
                ?>

			    </li>
			 <?php endforeach;?>
			 <?php if($user['User']['paper_count'] > 3):?>
			 	<li>
			 <?php echo __('Show all papers by').' '.$user['User']['username'];?>
			 	</li>
			 <?php endif;?>
			</ul>
<?php endif;?>