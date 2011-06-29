			<h6><?php echo __('Activity', true);?></h6>
			  <ul>
				<li><?php echo $user['User']['post_count'].' '.__('Posts', true)?></li>
				<li><?php echo $user['User']['posts_user_count'].' '.__('Reposts', true)?></li>
				<li><?php echo $user['User']['comment_count'].' '.__('Comments', true)?></li>
		   		<li><a id="show-subscribers" href="#show-subscribers" title="users/references/<?php echo $user['User']['id']; ?>"><?php echo $user['User']['content_paper_count'].' '.__('Subscribers', true)?></a></li>
				<li><?php echo $user['User']['subscription_count'].' '.__('Paper subscriptions', true)?></li>
				<li><?php echo $user['User']['paper_count'].' '.__('created Papers', true)?></li>
			</ul>
			<hr />
			
			
