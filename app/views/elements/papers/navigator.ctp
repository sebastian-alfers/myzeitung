<?php $paginator = $this->element('global/paginate');?>

<div id="maincolwrapper" class="paper-view">
    <div id="maincol">
         <?php if($this->params['action'] == 'index'):?>
              <h2><?php echo __('Browse Papers', true);?></h2>
        <?php endif;?>
        <?php if($this->params['controller'] == 'users' && $this->params['action'] == 'viewSubscriptions'):?>
            <?php if($own_paper == Paper::FILTER_OWN):?>
                <h2><?php echo sprintf(__('%1$s Papers',true),$this->MzText->possessive($user['User']['username']));?></h2>
            <?php elseif($own_paper == Paper::FILTER_SUBSCRIBED):?>
                <h2><?php echo sprintf(__('%1$s Paper Subscriptions',true),$this->MzText->possessive($user['User']['username']));?></h2>
            <?php endif;?>
        <?php endif;?>
			    	<div class="article-nav">
                         <?php echo $paginator ?>
					</div>

                      	<?php
                        foreach ($papers as $index => $paper):
						?>


						<div class="articlewrapper">
                            <?php if($paper['Paper']['owner_id'] == $session->read('Auth.User.id')): ?>
                                <span class="mypaper">mypaper</span>
                            <?php endif;?>
							<div class="article">
							<?php //<span class="round-icon">2</span> ?>
							<ul class="iconbar">
                                <?php // tt-title -> class for tipsy &&  'title=...' text for typsy'?>
                                <?php $tipsy_title = sprintf(__n('%s post', '%s posts', $paper['Paper']['post_count'],true), $this->MzNumber->counterToReadableSize($paper['Paper']['post_count']));?>
								<li class="articles tt-title" title="<?php echo $tipsy_title;?>"><?php echo $this->MzNumber->counterToReadableSize($paper['Paper']['post_count']);?></li>
                                <?php $tipsy_title = sprintf(__n('%s subscription', '%s subscriptions', $paper['Paper']['subscription_count'],true), $this->MzNumber->counterToReadableSize($paper['Paper']['subscription_count']));?>
								<li class="abos tt-title" title="<?php echo $tipsy_title;?>"><?php echo $this->MzNumber->counterToReadableSize($paper['Paper']['subscription_count']);?></li>
                                <?php $tipsy_title = sprintf(__n('%s author', '%s authors', $paper['Paper']['author_count'],true), $this->MzNumber->counterToReadableSize($paper['Paper']['author_count']));?>
								<li class="authors tt-title" title="<?php echo $tipsy_title;?>"><?php echo $this->MzNumber->counterToReadableSize($paper['Paper']['author_count']);?></li>
							</ul>
							<h4><?php echo $this->MzText->truncate($paper['Paper']['title'], 40,array('ending' => '...', 'exact' => false, 'html' => false));?></h4>
                                <?php
                                    $image_options = array();
                                    $image_options['url'] = $paper['Route'][0]['source'];
                                    $image_options['additional'] = 'margin-left:14px';
                                    echo $image->render($paper['Paper'], 110, 110, array(), $image_options, ImageHelper::PAPER);
                                ?>

							<ul class="footer">
                                <li>
                                <?php
                                    if(!empty($paper['User']['name'])){
                                        $name = $paper['User']['name'];
                                    }
                                    else{
                                        $name = $paper['User']['username'];
                                    }
                                    echo $this->Html->link(
                                            $image->render($paper['User'], 30, 30, array(), array('tag' => 'div', 'tag-class' => 'user-image nosubscribe'), ImageHelper::USER)
                                            .$name,
                                            array('controller' => 'users', 'action' => 'view', 'username' =>  strtolower($paper['User']['username'])),
                                            array('escape' => false, 'id' => $paper['User']['id'], 'alt' => $this->MzText->getUserName($paper['User']),'link' => $this->MzHtml->url(array('controller' => 'users', 'action' => 'view', 'username' => strtolower($paper['User']['username']))), 'rel' => $this->MzText->getSubscribeUrl(),'class' => 'user-image nosubscribe'));
                                    /*
                                     * .'<strong>'.$paper['User']['name'].'</strong><br />'.$this->MzText->truncate($paper['User']['name'], 15,array('ending' => '...', 'exact' => true, 'html' => false)),
                                     */
                                    ?>

								</li>
							</ul>
                            

							</div><!-- /.article -->
						</div><!-- / .articlewrapper -->

                        <?php endforeach;?>



				    <div class="article-nav article-nav-bottom">
                          <?php echo $paginator ?>
					</div><!-- / .article-nav -->	

					<div>

					</div>


					</div><!-- / #maincol -->

				</div><!-- / #maincolwrapper -->

