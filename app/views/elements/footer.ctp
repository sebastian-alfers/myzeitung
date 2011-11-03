
<div id="footer">
	<p>
		myZeitung © 2011 |
        <a href="#" class="mzslides"><?php __('Über uns'); ?></a> |
        <?php echo $this->Html->link(__('Impressum',true), array('controller' => 'pages', 'action' => 'display', 'impressum'), array('rel' => 'nofollow'));?> |
        <?php echo $this->Html->link(__('AGB',true), array('controller' => 'pages', 'action' => 'display', 'agb'), array('rel' => 'nofollow'));?> |
        <?php echo $this->Html->link(__('Datenschutzrichtlinien',true), array('controller' => 'pages', 'action' => 'display', 'dsr'), array('rel' => 'nofollow'));?> |
        <?php echo $this->Html->link(__('Kontakt',true), array('controller' => 'pages', 'action' => 'display', 'kontakt'), array('rel' => 'nofollow'));?> |
        <?php echo $this->Html->link(__('FAQ',true), '/p/myzeitung/faq-zeitung-deutsche-sprache', array('rel' => 'nofollow'));?>
        <?php if($this->Session->read('Auth.User.id') ): ?>
           | <?php echo $this->Html->link(__('Invite Friends', true), array('controller' => 'users' , 'action' => 'accInvitations'));?>
        <?php endif; ?>
	</p>
</div>

<!-- / #footer -->
<script type="text/javascript">
    var helpcenter = <?php echo isset($helpcenter_data)? json_encode($helpcenter_data) : "''"; ?>;
</script>
