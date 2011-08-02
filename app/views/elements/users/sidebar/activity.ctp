			<h6><?php echo __('Activity', true);?></h6>
			  <ul>
                  <?php $post_count =$user['User']['post_count'];?>
				<li><?php echo sprintf(__dn('default', '%d post', '%d posts', (int)$post_count,true), $user['User']['post_count']);?></li>
                <li><?php echo sprintf(__n('%d repost', '%d reposts', $user['User']['repost_count'],true), $user['User']['repost_count']);?></li>
                <li><?php echo sprintf(__n('%d comment', '%d comments', $user['User']['comment_count'],true), $user['User']['comment_count']);?></li>
		   		<li><a id="show-subscribers" href="#show-subscribers" title="users/references/<?php echo $user['User']['id']; ?>"><?php echo sprintf(__n('%d subscriber', '%d subscribers', $user['User']['content_paper_count'],true), $user['User']['content_paper_count']);?></a></li>
				<li><?php echo sprintf(__n('%d paper subscription', '%d paper subscriptions', $user['User']['subscription_count'],true), $user['User']['subscription_count']);?></li>
                <li><?php echo sprintf(__n('%d paper', '%d papers', $user['User']['paper_count'],true), $user['User']['paper_count']);?></li>
			</ul>
			<hr />
			
			
