<?php 
//debug($paper);
//debug($posts);die();?>

<?php echo $this->element('posts_navigator'); ?>	



<div class="actions">
<h2><?php  __('Paper');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $paper['Paper']['title']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Description'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $paper['Paper']['description']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Owner'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($paper['User']['username'],array('controller' => 'users', 'action' => 'view' ,$paper['User']['id'] )); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Url'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $paper['Paper']['url']; ?>
			&nbsp;
		</dd>
	</dl>
</div>


<div class="actions">
	<?php echo $this->element('navigation'); ?>	
	<h3><?php __('Filter by Category'); ?></h3>
	<ul>	
        <li><?php echo $this->Html->link(__('All Posts', true), array('controller' => 'papers',  'action' => 'view', $paper['Paper']['id'])); ?> </li>
        
        <?php if(count($paper['Category']) > 0): ?>
	        <?php foreach($paper['Category'] as $category):?>
	         	<li><?php echo $this->Html->link($category['name'], array('controller' => 'papers',  'action' => 'view', $paper['Paper']['id'] ,$category['id'] )); ?> </li>
	        <?php endforeach;?>
	    <?php endif; ?>
	</ul>
</div>

<?php //debug($contentReferences); ?>
<div>
	<ul>
	<?php foreach($contentReferences as $contentPaper):?>
		<li>
		<?php if($contentPaper['Topic']['id']): ?>
			<ul>
			<li>Reference to topic (<?php echo $contentPaper['Topic']['name']; ?>) from user (<?php echo $contentPaper['Topic']['User']['username']; ?>)</li>
			<li>Found Posts in this Topic: <?php echo count($contentPaper['Topic']['Post']); ?></li>
			<li>found post in index for this topic???</li>
			</ul> 
		<?php endif; ?>
		<?php if($contentPaper['User']['id']): ?>
			<ul>
			<li>Reference to whole user(<?php echo $contentPaper['User']['username']; ?>)<br /></li>
			<li>Found Posts from this user: <?php echo count($contentPaper['User']['Post']); ?></li>
			<li>found post in index for this user???</li>
			</ul> 		
			
		<?php endif; ?>		
		</li>
		<hr />
	<?php endforeach ?>
	</ul>
</div>