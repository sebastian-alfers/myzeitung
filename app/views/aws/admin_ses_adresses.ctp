<h2><?php __('List of verified email adresses'); ?></h2>
<?php __('These email addresses are allowed to send emails from myZeitung\'s servers as a sender'); ?>

<?php if(isset($data) && count($data) > 0): ?>

<div class="settings index">
	<table cellpadding="0" cellspacing="0">
	<tr>
	    <th><?php __('Email'); ?></th>
        <th><?php __('Action'); ?></th>
	</tr>
       <?php $i = 0; ?>
  <?php foreach($data->ListVerifiedEmailAddressesResult->VerifiedEmailAddresses->member as $email): ?>
        <?php

	    $class = null;
	    if ($i++ % 2 == 0)
	    $class = ' class="altrow"';
	    ?>
	    <tr<?php echo $class;?>>
            <td><?php echo $email; ?></td>
        </tr>
    <?php endforeach; ?>
     </table>
    </div>
<?php endif; ?>
