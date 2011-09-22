<?php if($user['User']['name']):?>
<p><strong><?php echo $user['User']['name'];?></strong></p>
<?php endif;?>
<?php if(!empty($user['User']['description'])):?>
<?php $about = strip_tags($user['User']['description']); ?>
<p class="aboutme"><i><?php echo $about; ?></i>
</p>
<?php if(strlen($about) > 120): ?>
    <a href="#" onclick="$('.aboutme-large').html($('.aboutme').html());$('#dialog-aboutme').dialog('open');"><?php echo "... " . __('read more', true); ?></a>
    <div id="dialog-aboutme" title="<?php echo sprintf(__('About %s', true),$user['User']['username']); ?>" style="display:none;">
        <div class="modal-content aboutme-large">

        </div>
    </div>
    <script type="text/javascript">
    $( "#dialog-aboutme" ).dialog({
        resizable: false,
        height:340,
        width:400,
        draggable:false,
        modal: true,
        autoOpen: false
    });
    </script>
<?php endif; ?>
<?php endif;?>
<?php if(!empty($user['User']['url'])):?>
<p class="user-url"><?php echo $this->Html->link($user['User']['url'],$user['User']['url'] , array('rel' => 'nofollow', 'target' => '_blank'))?></p>
<?php endif;?>
