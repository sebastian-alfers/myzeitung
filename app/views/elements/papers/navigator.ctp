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
							<h4><?php echo $paper['Paper']['title'];?></h4>
                            <?php echo $this->Html->link(
                                $image->render($paper['Paper'], 118, 100, array(), array(), ImageHelper::PAPER),
                                    array('controller' => 'papers', 'action' => 'view', $paper['Paper']['id']),
                                    array('escape' => false));?>

						

							<ul class="footer">
								<li><?php echo __('created by', true).':';?></li>


                               <?php  //debug( $image->render($paper['User'], 30, 30, array("class" => 'user-image'), array())); die();?>

                                <li>
                                <?php echo $this->Html->link(
                                            $image->render($paper['User'], 30, 30, array("class" => 'user-image'), array(), ImageHelper::USER)
                                            .'<strong>'.$paper['User']['username'].'</strong><br />'.$paper['User']['name'],
                                                array('controller' => 'users', 'action' => 'view', $paper['User']['id']),
                                                array('escape' => false));?>
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

