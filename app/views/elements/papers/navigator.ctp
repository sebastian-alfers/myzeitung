<div id="maincolwrapper" class="paper-view">
    <div id="maincol">
         <?php if($this->params['action'] == 'index'):?>
          <h2><?php echo __('Browse Papers', true);?></h2>
        <?php endif;?>
			    	<div class="article-nav">
                         <?php echo $this->element('global/paginate'); ?>
					</div>

                      	<?php
                        foreach ($papers as $index => $paper):
						?>


						<div class="articlewrapper">
							<div class="article">
							<?php //<span class="round-icon">2</span> ?>
							<ul class="iconbar">
                                <?php // tt-title -> class for tipsy &&  'title=...' text for typsy'?>
                                <?php $tipsy_title = sprintf(__n('%d post', '%d posts', $paper['Paper']['category_paper_post_count'],true), $paper['Paper']['category_paper_post_count']);?>
								<li class="articles tt-title" title="<?php echo $tipsy_title;?>"><?php echo $paper['Paper']['category_paper_post_count'];?></li>
                                <?php $tipsy_title = sprintf(__n('%d subscription', '%d subscriptions', $paper['Paper']['subscription_count'],true), $paper['Paper']['subscription_count']);?>
								<li class="abos tt-title" title="<?php echo $tipsy_title;?>"><?php echo $paper['Paper']['subscription_count'];?></li>
                                <?php $tipsy_title = sprintf(__n('%d author', '%d authors', $paper['Paper']['content_paper_count'],true), $paper['Paper']['content_paper_count']);?>
								<li class="authors tt-title" title="<?php echo $tipsy_title;?>"><?php echo $paper['Paper']['content_paper_count'];?></li>
							</ul>
							<h4><?php echo $this->MzText->truncate($paper['Paper']['title'], 20,array('ending' => '...', 'exact' => false, 'html' => false));?></h4>
                                <?php
                                    $image_options = array();
                                    $image_options['url'] = array('controller' => 'papers', 'action' => 'view', $paper['Paper']['id']);
                                    $image_options['additional'] = 'margin-left:14px';
                                    echo $image->render($paper['Paper'], 110, 110, array(), $image_options, ImageHelper::PAPER);
                                ?>

							<ul class="footer">
								<li><?php echo __('created by', true).':';?></li>


                               <?php  //debug( $image->render($paper['User'], 30, 30, array("class" => 'user-image'), array())); die();?>

                                <li>
                                    <?php
                                   // $image_options = array();
                                   // $image_options['url'] = array('controller' => 'users', 'action' => 'view', $paper['User']['id']);
                                   // $image_options['class'] ='user-image';
                                    //echo $image->render($paper['User'], 30, 30, array(), $image_options, ImageHelper::USER);
                                ?>
                                <?php echo $this->Html->link(
                                            $image->render($paper['User'], 30, 30, array(), array('tag' => 'div', 'tag-class' => 'user-image-container'), ImageHelper::USER)
                                            .'<strong>'.$paper['User']['username'].'</strong><br />'.$this->MzText->truncate($paper['User']['name'], 15,array('ending' => '...', 'exact' => true, 'html' => false)),
                                                array('controller' => 'users', 'action' => 'view', $paper['User']['id']),
                                                array('escape' => false, 'id' => $paper['User']['id'], 'alt' => $this->MzText->getUserName($paper['User']), 'rel' => $this->MzText->getSubscribeUrl(),'class' => 'user-image'));?>
								</li>
							</ul>
                            

							</div><!-- /.article -->
						</div><!-- / .articlewrapper -->

                        <?php endforeach;?>



				    <div class="article-nav article-nav-bottom">
                          <?php echo $this->element('global/paginate'); ?>
					</div><!-- / .article-nav -->	

					<div>

					</div>


					</div><!-- / #maincol -->

				</div><!-- / #maincolwrapper -->

