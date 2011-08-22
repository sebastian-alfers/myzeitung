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
                <?php echo $this->Html->link($complaint['Paper']['title'], $complain['Route'][0]['source'], array('target' => 'blank'));?>

                &nbsp;
            </dd>
        <?php endif; ?>
        <?php if(!empty($complaint['Post']['id'])): ?>
            <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Post'); ?></dt>
            <dd<?php if ($i++ % 2 == 0) echo $class;?>>
              <?php __('Post:'); echo $this->Html->link($complaint['Post']['title'], $complain['Route'][0]['source'], array('target' => 'blank'));?>
                &nbsp;
            </dd>
        <?php endif; ?>

        <?php if(!empty($complaint['Comment']['id'])): ?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Comment'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>

            <?php __('Comment:'); echo $this->Html->link($complaint['Comment']['text'], $complain['Route'][0]['source'].'#comment_'.$complaint['Comment']['id'], array('target' => 'blank'));?>
			&nbsp;
		</dd>
		<?php endif; ?>

        <?php if(!empty($complaint['User']['id'])): ?>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('User'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($complaint['User']['username'], array('controller' => 'users', 'action' => 'view','username' =>  strtolower($complaint['User']['username']))); ?>
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
               <?php echo $this->Html->link($complaint['User']['username'], array('controller' => 'users', 'action' => 'view','username' =>  strtolower($complaint['User']['username']))); ?>
            <?php endif; ?>
            <?php echo $complaint['Complaint']['reporter_email']; ?>
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Email', true); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $complaint['Complaint']['reporter_email']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Status'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $complaint['Complaintstatus']['value']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->MzTime->timeAgoInWords($complaint['Complaint']['created'], array('format' => 'd.m.y  h:m','end' => '+1 Month')); ?>
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $this->MzTime->timeAgoInWords($complaint['Complaint']['modified'], array('format' => 'd.m.y  h:m','end' => '+1 Month')); ?>
		</dd>
	</dl>
</div>