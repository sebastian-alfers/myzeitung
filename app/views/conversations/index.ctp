<?php echo $this->element('users/sidebar'); ?>
				<div id="maincolwrapper">
					<div id="maincol" class="account message-overview">
						<h4 class="account-title"><?php echo __('Conversations', true);?></h4>

						<div class="message-top-nav" >
                            <?php /*
						<a href="" class="btn btn-send"><span class="send-icon"></span>Neue Nachricht</a>

							<form id="search-messages" action="search-result.html" class="">
								<input class="searchinput" type="text" onblur="if (this.value == '') {this.value = 'User durchsuchen';}" onfocus="if (this.value == 'User durchsuchen') {this.value = '';}" value="Nachrichten durchsuchen" />
								<button class="submit" type="submit" value="">Suchen</button>
							</form> */ ?>
						</div>

						<ul class="messages">

                            <?php foreach($conversations as $conversation):?>
							<li class="message">
								<ul>
									<li class="state">
                                        <?php if($conversation['ConversationUser']['status'] == conversation::STATUS_NEW):?>
										    <a class="icon  icon-state icon-unread"></a>
                                        <?php else:?>
                                           <a class="icon  icon-state icon-read"></a>
                                        <?php endif;?>
									</li>


                                    <?php // PICTURE HANDLING ?>
									<li class="user-image-container">
                                        <?php if(count($conversation['Conversation']['ConversationUser']) > 2):
                                            // show 2 pictures if more than 2 participants (--> at least 2 more than your self)
                                            $picture_count = 0;
                                            foreach($conversation['Conversation']['ConversationUser'] as $ConversationUser){
                                                //display first two pictures of users, other than the logged in user
                                                if($ConversationUser['User']['id'] !=$session->read('Auth.User.id')){
                                                    $picture_count +=1;
                                                    if($picture_count == 1):?>
                                                        <div class="multible-message-users first">
                                                    <?php else:?>
                                                        <div class="multible-message-users">
                                                    <?php endif;?>
                                                     <?php echo  $image->render($ConversationUser['User'], 37, 37, array( "alt" => $ConversationUser['User']['username']), array('tag' => 'div'), ImageHelper::USER);?>
                                                     </div>
                                                        <?php
                                                }
                                                if($picture_count == 2) break;
                                            }
                                            ?>
                                        <?php else:
                                            //show only 1 picture - just one participant other than the logged in user.
                                            // check which one to take
                                            $picture_index = 0;
                                            if($conversation['Conversation']['ConversationUser'][1]['User']['id'] != $session->read('Auth.User.id')){
                                                $picture_index = 1;
                                            }
                                            echo  $image->render($conversation['Conversation']['ConversationUser'][$picture_index]['User'], 37, 37, array( "alt" => $conversation['Conversation']['ConversationUser'][$picture_index]['User']['username']), array('tag' => 'div'), ImageHelper::USER);
                                        endif;?>



									</li>
									<li class="is-answer">
                                        <?php if($conversation['ConversationUser']['status'] == conversation::STATUS_REPLIED):?>
										    <span class="icon icon-isanswer"></span>
                                        <?php endif;?>
									</li>
									<li class="message-info">
										<h5><?php echo $this->Html->link($conversation['Conversation']['title'],array('controller' => 'conversations', 'action' => 'view', $conversation['Conversation']['id']));?></h5>
										<?php // show all participants ?>
                                        <p class="from"><?php echo sprintf(__('between you and', true));?>
                                            <?php foreach($conversation['Conversation']['ConversationUser'] as $user){
                                                if($user['User']['id'] != $session->read('Auth.User.id')){
                                                    $tipsy_name= $user['User']['username'];
									                if($user['User']['name']){
										                $tipsy_name = $user['User']['username'].' - '.$user['User']['name'];
									                }
                                                    echo '<strong>'.$this->Html->link('<strong>'.$user['User']['username'].'</strong>' ,array('controller' => 'users', 'action' => 'view', $user['User']['id']), array('class' => 'user-image tt-title', 'title' => $tipsy_name, 'escape' => false)).'</strong>';
                                                  
                                                }
                                            }?>

                                        </p>

                                		<p class="excerpt"><?php echo $this->Html->link($this->MzText->truncate($conversation['Conversation']['LastMessage']['message'], 160 ,array('ending' => '...', 'exact' => false, 'html' => false)),array('controller' => 'conversations', 'action' => 'view', $conversation['Conversation']['id']));?></p>
									</li>
									<li class="actions">
                                        <?php echo $this->Html->link('' ,array('controller' => 'conversations', 'action' => 'view', $conversation['Conversation']['id']), array('class' => 'icon icon-answer', 'escape' => false));?>
									    <?php echo $this->Html->link('',array('controller' => 'conversations', 'action' => 'remove', $conversation['Conversation']['id']), array('class' => 'icon icon-delete', 'escape' => false));?>
										
										<p><?php echo $this->MzTime->timeAgoInWords($conversation['Conversation']['LastMessage']['created'], array('format' => 'd.m.y  h:m','end' => '+1 Month'));?></p>
									</li>

								</ul>
							</li><!-- /.message -->
                            <?php endforeach;?>

						</ul>

						<div class="pagination">
							 <?php echo $this->element('global/paginate'); ?>
						</div>



					</div><!-- / #maincol -->

				</div><!-- / #maincolwrapper -->
