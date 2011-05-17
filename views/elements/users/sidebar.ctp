<?php 
/*

<script type="text/javascript">

$(document).ready(function() {
	
	$('#link_subscribe').bind('click', function(){subscribeDialog(this);});
});	

function subscribeDialog(element){
	var user_id = $(element).attr('name');
	var req = $.post(base_url + '/users/ajxSubscribe.json', {id:user_id})
		.success(function( obj ){
			$('#dialog-subscribe').html(obj.type);
			$('#dialog-subscribe').dialog('open');		
		})		   
		.error(function(){
   			alert('error');
	});						
	
		
	return false;
}

$(function() {

	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#dialog-subscribe" ).dialog({
		resizable: false,
		height:240,
		width:400,
		draggable:false,
		modal: true,
		autoOpen: false,
		buttons: [{
            id:"btn-save-subscribe",
            text: "Save Subscription",
            click: function() {
                    $(this).dialog("close");
            }
    	},{
            id:"btn-cancel",
            text: "Cancel",
            click: function() {
                    $(this).dialog("close");
            }
    	}]//end button
	});//end button .dialog	
});
//
</script>

<?php echo $this->element('users/modal_subscribe'); ?>
*/
?>

<div id="leftcolwapper">
<div class="leftcol">
	<div class="leftcolcontent">
			<div class="userstart">
				<?php
				$img_data = $image->getUserImpPath($user['User']['image']);
				if(is_array($img_data)){
					
					//debug($img_data);die();
					//found img in db
					$info = $image->resize($img_data['path'], 185, 185, $img_data['size'], true);
					echo $this->Html->image($info['path'], array("class" => "userimage", "alt" => $user['User']['username']."-image", 'style' => $info['inline']));
				}
				else{
					$info = $image->resize($img_data, 185, 185, null, false);
					echo $this->Html->image($info, array("class" => "userimage", "alt" => $user['User']['username']."-image"));
				}
				?>
				
				<?php if($user['User']['id'] != $session->read('Auth.User.id')): //can not subscribe to himself - cannot send a message to himself ?>
					<?php echo $this->Html->link('<span>+</span>'.__('Subscribe', true), array('controller' => 'users',  'action' => 'subscribe', $user['User']['id']), array('escape' => false, 'class' => 'btn', ));?>
					<?php echo $this->Html->link('<span>+</span>'.__('Send Message', true), array('controller' => 'conversations', 'action' => 'add', $user['User']['id']), array('escape' => false, 'class' => 'btn', ));?>
				<?php endif; ?>
			</div>
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
			<?php if($this->params['controller'] == 'users' && ($this->params['action'] == 'accImage' || $this->params['action'] == 'accGeneral' || $this->params['action'] == 'accPrivacy' || $this->params['action'] == 'accAboutMe')):?>
				<?php echo $this->element('users/sidebar/account_menue'); ?>
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
		 </div><!-- /.leftcolcontent -->	
		</div><!-- /.leftcol -->
		
</div><!-- / #leftcolwapper -->