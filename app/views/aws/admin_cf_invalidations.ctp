<h2><?php __('CloudFront Invalidations'); ?></h2>
<?php if(!empty($data)): ?>
    <p>
        <h3>Note: an invalidation request can take up to 15 minutes to take effect on all CloudFront nodes worldwide!</h3>
    </p>
    <table cellpadding="0" cellspacing="0">
        <tbody><tr>
            <th>ID</th>
            <th>Created (GMT)</th>
            <th>Path</th>
            <th>Status</th>
        </tr>
    </tbody>
    <?php foreach($data as $invalidation_request): ?>
        <tr class="altrow">
            <td><?php echo $invalidation_request['id']; ?></td>
            <td><?php echo $invalidation_request['created']; ?></td>
            <td><?php echo $invalidation_request['path']; ?></td>
            <td><?php echo $invalidation_request['status']; ?></td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>
<hr />
<div class="createinvalidation form">
<p>
    <h3>Note: a path MUST start within the <i>/img</i>-Folder like: <i><strong>/img</strong>/assets/maintile.png</i></h3>
</p>
<form id="CreateInvalidation/admin/aws/cf/invalidationsForm" method="post" action="/admin/aws/cf/createinvalidation" accept-charset="utf-8">
	<fieldset>
		<legend><?php __('Create Invalidation'); ?></legend>
        <?php echo $form->input('Path.Paths', array('multiple' => 'checkbox', 'options' => $paths, 'selected' => array())); ?>
        <?php echo $this->Form->input('path', array('label' => 'Path (seperate multiple paths with comma (,)')); ?>
		<?php echo $this->Form->input('distribution_id', array('type' => 'hidden', 'value' => $distribution_id)); ?>
        <div class="submit"><input type="submit" value="<?php __('Submit Invalidation Request'); ?>"></div>
	</fieldset>
</div>