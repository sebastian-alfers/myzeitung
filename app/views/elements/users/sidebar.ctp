<?php 
echo $this->element('users/modal_subscribe');
?>

<script type="text/javascript">

$(document).ready(function() {
	
	$('.subscribe-user').bind('click', function(){subscribeDialog(this);});

    $('#btn-submit-subscription').bind('click', function(){
        $('#UserSubscribeForm').submit();
    });

});	

function subscribeDialog(element){
	loadForm();
    $('#dialog-subscribe').dialog('open');
		
	return false;
}

function loadForm(target_id, target_type){
    $('#dialog-subscribe-content').html("");
    var req = $.post(base_url + '/users/subscribe/1')
       .success(function( string ){
           $('#dialog-subscribe-content').html(string);
       })
       .error(function(){
           alert('error');
    });
}

$(function() {

	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#dialog-subscribe" ).dialog({
		resizable: false,
		height:340,
		width:400,
		draggable:false,
		modal: true,
		autoOpen: false
    });//end button .dialog
});
//
</script>


<?php echo $this->element('users/modal_activity'); ?>

<div id="leftcolwapper">
<div class="leftcol">
	<div class="leftcolcontent">
			<div class="userstart">
				<?php
				$link_data = array();
				$link_data['url'] = array('controller' => 'users', 'action' => 'view', $user['User']['id']);
				$link_data['additional'] = array('class' => 'user-image');
				echo $image->render($user['User'], 185, 185, array("alt" => $user['User']['username']), $link_data); ?>
			</div>

			<?php echo $this->element('users/sidebar/buttons'); ?>
			<h4><?php echo $user['User']['username'];?></h4>
			<?php //elements shown when being on actions users-view, posts-view ?>
			<?php if(($this->params['controller'] == 'users' && $this->params['action'] == 'view') || ($this->params['controller'] == 'posts' && $this->params['action'] == 'view')):?>
				<?php echo $this->element('users/sidebar/info'); ?>
				<?php echo $this->element('users/sidebar/topics'); ?>
				<?php echo $this->element('users/sidebar/activity'); ?>
				<?php echo $this->element('users/sidebar/papers'); ?>
			<?php endif;?>
			<?php //elements shown when being on actions users-viewSubscriptions ?>
			<?php if($this->params['controller'] == 'users' && $this->params['action'] == 'viewSubscriptions'):?>
				<?php echo $this->element('users/sidebar/subscriptions'); ?>
			<?php endif;?>
			<?php //elements shown when being on actions in users-account settings ?>
			<?php if($this->params['controller'] == 'users' && ($this->params['action'] == 'accDelete' || $this->params['action'] == 'accImage' || $this->params['action'] == 'accGeneral' || $this->params['action'] == 'accPrivacy' || $this->params['action'] == 'accAboutMe')):?>
				<?php echo $this->element('users/sidebar/account_menue', array('user_id' => $user['User']['id'])); ?>
			<?php endif;?>
			
		
<?php /*?>
	<hr />
		<h6><?php __('Writes for'); ?></h6>
				<ul>
				<?php foreach($wholeUserReferences as $reference): ?>
					<li><?php echo $reference['Paper']['title']?> <?php if($reference['Category']['id'] == '') echo " (direct in paper)" ?></li>
				<?php endforeach; ?>
				</ul>
				<ul>
				<?php foreach($topicReferences as $reference): ?>
					<li><?php echo $reference['Topic']['name']?> (topic) <?php if($reference['Category']['id'] == '') echo " (direct in paper)" ?></li>
				<?php endforeach; ?>
				</ul>			
			<?php */?>

        <hr />
        <?php if($this->params['controller'] == 'users'): ?>
            <?php echo $this->element('complaints/button', array('model' => 'user', 'complain_target_id' => $user['User']['id'])); ?>
        <?php endif; ?>
        <?php if($this->params['controller'] == 'posts'): ?>
            <?php echo $this->element('complaints/button', array('model' => 'post', 'complain_target_id' => $post['Post']['id'])); ?>
        <?php endif; ?>
		 </div><!-- /.leftcolcontent -->	
		</div><!-- /.leftcol -->
		
</div><!-- / #leftcolwapper -->