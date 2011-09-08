<h2><?php __('Cloudfront Distributions'); ?></h2>
<?php if(isset($data->body->DistributionSummary->Id)): ?>
<table cellpadding="0" cellspacing="0">
	<tbody><tr>
	    <th>ID</th>
        <th>Status</th>
        <th>Domain</th>
        <th>Origin</th>
        <th class="actions"><?php __('Actions'); ?></th>
	</tr>
</tbody>
	<tr class="altrow">
		<td><?php echo $data->body->DistributionSummary->Id; ?></td>
        <td><?php echo $data->body->DistributionSummary->Status; ?></td>
        <td><?php echo $data->body->DistributionSummary->DomainName; ?></td>
        <td><?php echo $data->body->DistributionSummary->CustomOrigin->DNSName; ?></td>
        <td class="actions"><?php echo $this->Html->link(__('Invalidations', true), array('controller' => 'aws/cf', 'action' => 'invalidations', $data->body->DistributionSummary->Id)); ?></td>
    </tr>

</table>
<?php endif; ?>