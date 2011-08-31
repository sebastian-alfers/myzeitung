<?php
if(count($references) > 0):
	for($i = 0; $i < count($references); $i++): ?>

        <?php $reference = $references[$i];  ?>


        <?php if($owner): ?>
	    	<div style="float:left;margin:0 5px 5px 0;"><div id="link-del<?php echo $reference['ContentPaper']['id']; ?>" class="tt-title link-delete">x</div>
        <?php else: ?>
            <div style="float:left;margin:0 5px 5px 0;"><div class="tt-title link-delete"></div>
        <?php endif; ?>

        <?php
		$link_data = array();
		$link_data['url'] = array('controller' => 'users', 'action' => 'view', 'username' => strtolower($reference['User']['username']));
		if(!$owner){
            $link_data['custom'] = array('class' => 'user-image', 'alt' => $this->MzText->getUsername($reference['User']), 'link' => $this->MzHtml->url(array('controller' => 'users', 'action' => 'view', 'username' => strtolower($reference['User']['username']))), 'rel' => $this->MzText->getSubscribeUrl(), 'id' => $reference['User']['id']);
        }
        $link_data['additional'] = "display:block;";
		$name = $reference['User']['username'];
		if(isset($reference['User']['name']) && !empty($reference['User']['name'])) $name.= " (".$reference['User']['name'].")";

        echo $image->render($reference['User'], 50, 50, array("alt" => $reference['User']['username'], 'title' => $name, 'class' => 'img-paper-user tt-title', 'id' => 'del'. $reference['ContentPaper']['id']), $link_data); ?></div>
		<?php /* if(!empty($reference['User']['id'])): ?>
		 <li><?php echo $reference['User']['name'] ?> (whole user)</li>
		<?php endif; ?>

		<?php if(!empty($reference['Topic']['id'])): ?>

		<li><?php echo $reference['Topic']['name']; ?> (topic from user <?php echo  $reference['Topic']['User']['name'] ?> )</li>
		<?php endif; */ ?>
	<?php endfor; ?>

<?php endif; ?>

<?php //echo $this->Html->link('< ' . __('Back to Paper', true), array('controller' => 'papers', 'action' => 'view', $paper_id)); ?>

<script>
$('.tt-title').tipsy({ fade: false, opacity: 1, gravity: 'sw'});

function listenHover(element){
    id = $(element).attr('id');
    $('#link-'+id).show();
    //remove hover listener
    //$('.img-paper-user').die('hover');
}

function removeListenHover(element){
    id = $(element).attr('id');

    $('#link-'+id).hide();
}

//show link to delte user
$('.img-paper-user').live('hover', function(){
    listenHover(this);

});

//hide link to delte user
$('.img-paper-user').live('mouseout', function(){
    removeListenHover(this);
});


$('.link-delete').live('hover', function(){
    //add hover stlye
    $(this).addClass('hover_remove_btn');

    //extract id for image from id from link
    link_id = $(this).attr('id');
    len = "link-";
    img_id = link_id.substring(len.length, link_id.length);

    listenHover($('#'+img_id));
});


$('.link-delete').live('mouseout', function(){
    img_id = $(this).attr('id');

    //listenHover(this);
    $(this).removeClass('hover_remove_btn');
});

</script>