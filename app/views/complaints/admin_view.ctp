<div class="complaints view">
<h2><?php  __('Complaint');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $complaint['Complaint']['id']; ?>
			&nbsp;
		</dd>
        <?php if(!empty($complaint['Paper']['id'])): ?>
            <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Paper'); ?></dt>
            <dd<?php if ($i++ % 2 == 0) echo $class;?>>
                <a href="/papers/view/<?php echo $complaint['Paper']['id']; ?>" target="blank"><?php echo $complaint['Paper']['title']; ?></a>
                &nbsp;
            </dd>
        <?php endif; ?>
        <?php if(!empty($complaint['Post']['id'])): ?>
            <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Post'); ?></dt>
            <dd<?php if ($i++ % 2 == 0) echo $class;?>>
                <a href="/posts/view/<?php echo $complaint['Post']['id']; ?>" target="blank"><?php echo $complaint['Post']['title']; ?></a>
                &nbsp;
            </dd>
        <?php endif; ?>

        <?php if(!empty($complaint['Comment']['id'])): ?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Comment'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <a href="/posts/view/<?php echo $complaint['Comment']['post_id']; ?>" target="blank"><?php echo $complaint['Comment']['text']; ?></a>
			&nbsp;
		</dd>
		<?php endif; ?>

        <?php if(!empty($complaint['User']['id'])): ?>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('User'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($complaint['User']['name'], array('controller' => 'users', 'action' => 'view', $complaint['User']['id'])); ?>
			&nbsp;
		</dd>
        <?php endif; ?>

		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Reason'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php __($complaint['Reason']['value']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Comments'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <div>
            <?php
                echo $this->element('complaints/admin_comments', array('comments' => $complaint['Complaint']['comments']));
            ?>
            </div>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Reporter'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php if(isset($complaint['Reporter']['id']) && !empty($complaint['Reporter']['id'])): ?>
               <a href="/users/view/<?php echo $complaint['Reporter']['id']; ?>" target="blank"><?php echo $complaint['Reporter']['name']; ?></a>
            <?php endif; ?>
            <?php echo $complaint['Complaint']['reporter_email']; ?>
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Reporter Email'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $complaint['Complaint']['reporter_email']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Complaintstatus'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $complaint['Complaintstatus']['value']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Time->timeAgoInWords($complaint['Complaint']['created'], array('end' => '+1 Week')); ?>
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $this->Time->timeAgoInWords($complaint['Complaint']['modified'], array('end' => '+1 Week')); ?>
		</dd>
	</dl>
</div>