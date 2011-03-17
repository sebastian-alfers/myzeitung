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