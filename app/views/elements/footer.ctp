
<div id="footer">
	<p>
		myZeitung © 2011 |
        <?php echo $this->Html->link(__('Impressum',true), array('controller' => 'pages', 'action' => 'display', 'impressum'), array('rel' => 'nofollow'));?> |
        <?php echo $this->Html->link(__('AGB',true), array('controller' => 'pages', 'action' => 'display', 'agb'), array('rel' => 'nofollow'));?> |
        <?php echo $this->Html->link(__('Datenschutzrichtlinien',true), array('controller' => 'pages', 'action' => 'display', 'dsr'), array('rel' => 'nofollow'));?> |
         <?php echo $this->Html->link(__('Kontakt',true), array('controller' => 'pages', 'action' => 'display', 'kontakt'), array('rel' => 'nofollow'));?>
	</p>
</div>

<!-- / #footer -->
<script type="text/javascript">
    var helpcenter = <?php echo isset($helpcenter_data)? json_encode($helpcenter_data) : "''"; ?>;
    var default_helptext = '<?php echo isset($default_helptext)? $default_helptext : ''; ?>';
</script>
