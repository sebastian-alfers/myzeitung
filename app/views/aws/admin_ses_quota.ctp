<h2><?php __('Statistics (quota) of email'); ?></h2>
<?php if((isset($quota) && count($quota) > 0) || (isset($data) && count($data) > 0)): ?>

<div class="settings index">
	<table cellpadding="0" cellspacing="0">
	<tr>
	    <th><?php __('Metric'); ?></th>
        <th><?php __('Value'); ?></th>
        <th><?php __('Description'); ?></th>
	</tr>

        <?php if(isset($quota) && count($quota) > 0): ?>
            <tr class="altrow">
                <td>Sent during Last 24 Hours</td>
                <td><?php echo $quota->GetSendQuotaResult->SentLast24Hours; ?></td>
                <td>The number of emails sent during the previous 24 hours.</td>
            </tr>
            <tr>
                <td>Max emails per 24 Hour Send</td>
                <td><?php echo $quota->GetSendQuotaResult->Max24HourSend; ?></td>
                <td>The maximum number of emails the user is allowed to send in a 24-hour interval.</td>
            </tr>
            <tr class="altrow">
                <td>Max Send Rate</td>
                <td><?php echo $quota->GetSendQuotaResult->MaxSendRate; ?></td>
                <td>The maximum number of emails the user is allowed to send per second.</td>
            </tr>
        </table>
        <?php endif; ?>
        <br />
        <?php if(isset($data) && count($data) > 0): ?>
            <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?php __('Metric'); ?></th>
                <th><?php __('Value (for the last 14 days)'); ?></th>
                <th><?php __('Description'); ?></th>
            </tr>
            <tr class="altrow">
                <td>Delivery Attempts</td>
                <td><?php echo $data['DeliveryAttempts']; ?></td>
                <td>Number of emails that have been enqueued for sending.</td>
            </tr>
            <tr>
                <td>Rejects</td>
                <td><?php echo $data['Rejects']; ?></td>
                <td>Number of emails rejected by Amazon SES.</td>
            </tr>
            <tr class="altrow">
                <td>Bounces</td>
                <td><?php echo $data['Bounces']; ?></td>
                <td>Number of emails that have bounced.</td>
            </tr>
            <tr>
                <td>Complaints</td>
                <td><?php echo $data['Complaints']; ?></td>
                <td>Number of unwanted emails that were rejected by recipients.</td>
            </tr>
        </table>
        <?php endif; ?>


    </div>
<?php endif; ?>
