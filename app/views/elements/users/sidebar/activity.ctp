			<h6><?php echo __('Activity', true);?></h6>
			  <ul>
                  <?php $post_count =$user['User']['post_count'];?>
				<li><?php echo sprintf(__n('%d post', '%d posts', $post_count,true), $user['User']['post_count']);?></li>
                <li><?php echo sprintf(__n('%d pepost', '%d reposts', $user['User']['posts_user_count'],true), $user['User']['posts_user_count']);?></li>
                <li><?php echo sprintf(__n('%d comment', '%d comments', $user['User']['comment_count'],true), $user['User']['comment_count']);?></li>
		   		<li><a id="show-subscribers" href="#show-subscribers" title="users/references/<?php echo $user['User']['id']; ?>"><?php echo sprintf(__n('%d subscriber', '%d subscribers', $user['User']['content_paper_count'],true), $user['User']['content_paper_count']);?></a></li>
				<li><?php echo sprintf(__n('%d paper subscription', '%d paper subscriptions', $user['User']['subscription_count'],true), $user['User']['subscription_count']);?></li>
                <li><?php echo sprintf(__n('%d paper', '%d papers', $user['User']['paper_count'],true), $user['User']['paper_count']);?></li>
			</ul>
			<hr />
			
			
