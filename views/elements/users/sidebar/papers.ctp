<?php if($user['User']['paper_count'] > 0):?>
			<h6><?php echo __('Top Papers by',true).' '.$user['User']['username']?></h6>
			<ul class="newslist">
			<?php foreach($user['Paper'] as $paper):?>
				<li>
				<?php /* image */
                $link_data = array();
                $link_data['url'] = array('controller' => 'papers', 'action' => 'view', $paper['id']);
                //$link_data['additional'] = array('class' => 'user-image');
                echo $image->render($paper, 35, 35, array("alt" => $paper['title']), $link_data, ImageHelper::PAPER);
                //echo  $this->Html->link($this->Html->image($image->resize($paper['image'], 35, 35)) , array('controller' => 'papers', 'action' => 'view', $paper['id']),array('escape' => false) );?>
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