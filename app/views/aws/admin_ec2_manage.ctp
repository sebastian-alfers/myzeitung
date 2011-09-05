<?php if(isset($data) && count($data) > 0): ?>

<div class="settings index">
	<h2><?php __('Settings');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
	    <th><?php __('Name'); ?></th>
        <th><?php __('Id'); ?></th>
        <th><?php __('State'); ?></th>
        <th><?php __('Type'); ?></th>
        <th><?php __('Time'); ?></th>
        <th><?php __('Zone'); ?></th>
	</tr>

    <?php $i = 0; ?>
    <?php foreach($data as $instance): ?>
	<?php
        debug($instance);
	$class = null;
	if ($i++ % 2 == 0)
	    $class = ' class="altrow"';
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $instance['name']; ?></td>
        <td><?php echo $instance['id']; ?></td>
        <td><?php echo $instance['state']; ?></td>
        <td><?php echo $instance['type']; ?></td>
        <td><?php echo $instance['time']; ?></td>
        <td><?php echo $instance['loc']; ?></td>
    </tr>
    <?php endforeach; ?>
     </table>
    </div>
<?php endif; ?>