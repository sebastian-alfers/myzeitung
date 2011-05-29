<?php //debug($wholeUserReferences); ?>
Whole user associations:
<ul>
<?php foreach($wholeUserReferences as $reference): ?>
	<li><?php echo $reference['Paper']['title']?> <?php if($reference['Category']['id'] == '') echo " (direct in paper)" ?></li>
<?php endforeach; ?>
</ul>
<br />
<hr />
<br />
Associations to Topics:
<ul>
<?php foreach($topicReferences as $reference): ?>
	<li><?php echo $reference['Topic']['name']?> <?php if($reference['Category']['id'] == '') echo " (direct in paper)" ?></li>
<?php endforeach; ?>
</ul>