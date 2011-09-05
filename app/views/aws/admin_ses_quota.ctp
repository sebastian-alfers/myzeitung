<h2><?php __('Statistics (quota) of email'); ?></h2>
<?php if(isset($data) && count($data) > 0): ?>

<div class="settings index">
	<table cellpadding="0" cellspacing="0">
	<tr>
	    <th><?php __('Metric'); ?></th>
        <th><?php __('Value'); ?></th>
        <th><?php __('Description'); ?></th>
	</tr>
	<tr class="altrow">
		<td>Sent during Last 24 Hours</td>
        <td><?php echo $data->GetSendQuotaResult->SentLast24Hours; ?></td>
        <td>The maximum number of emails the user is allowed to send in a 24-hour interval.</td>
    </tr>
	<tr>
		<td>Max emails per 24 Hour Send</td>
        <td><?php echo $data->GetSendQuotaResult->Max24HourSend; ?></td>
        <td>The number of emails sent during the previous 24 hours.</td>
    </tr>
	<tr class="altrow">
		<td>Max Send Rate</td>
        <td><?php echo $data->GetSendQuotaResult->MaxSendRate; ?></td>
        <td>The maximum number of emails the user is allowed to send per second.</td>
    </tr>


     </table>
    </div>
<?php endif; ?>
