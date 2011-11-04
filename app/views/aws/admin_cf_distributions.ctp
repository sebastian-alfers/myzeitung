<h2><?php __('Cloudfront Distributions'); ?></h2>
<?php if(isset($data->body->DistributionSummary->Id)): ?>
        <?php debug($data); ?>
<table cellpadding="0" cellspacing="0">
	<tbody><tr>
	    <th>ID</th>
        <th>Status</th>
        <th>Domain</th>
        <th>DNS Name</th>
        <th>Origin</th>
        <th class="actions"><?php __('Actions'); ?></th>
	</tr>
</tbody>
    <?php foreach($data->body->DistributionSummary as $distri): ?>
        <tr class="altrow">
            <td><?php echo $distri->Id; ?></td>
            <td><?php echo $distri->Status; ?> - Enabled: <?php echo $distri->Enabled; ?></td>
            <td><?php echo $distri->DomainName; ?></td>
            <td><?php echo $distri->CustomOrigin->DNSName; ?></td>
            <td class="actions"><?php echo $this->Html->link(__('Invalidations', true), array('controller' => 'aws/cf', 'action' => 'invalidations', $distri->Id)); ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>