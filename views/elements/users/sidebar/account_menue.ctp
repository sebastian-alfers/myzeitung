							<ul class="my-account-nav">
			
								<?php if($this->params['controller'] == 'users' && $this->params['action'] == 'accSettings'):?><li class="active"><?php else:?><li><?php endif;?>
								<?php echo $this->Html->link('<span class="icon icon-general"></span>'.__('General Settings', true), array('controller' => 'users', 'action' => 'accGeneral'), array('escape' => false,));?></li>
								
								<?php if($this->params['controller'] == 'users' && $this->params['action'] == 'accAboutMe'):?><li class="active"><?php else:?><li><?php endif;?>
								<?php echo $this->Html->link('<span class="icon icon-about"></span>'.__('About Me', true), array('controller' => 'users', 'action' => 'accAboutMe'), array('escape' => false,));?></li>
							
								<?php if($this->params['controller'] == 'users' && $this->params['action'] == 'accImage'):?><li class="active"><?php else:?><li><?php endif;?>
								<?php echo $this->Html->link('<span class="icon icon-profilpic"></span>'.__('Profile Picture', true), array('controller' => 'users', 'action' => 'accImage'), array('escape' => false,));?></li>
							
								<?php if($this->params['controller'] == 'users' && $this->params['action'] == 'accPrivacy'):?><li class="active"><?php else:?><li><?php endif;?>
								<?php echo $this->Html->link('<span class="icon icon-privacy"></span>'.__('Privacy', true), array('controller' => 'users', 'action' => 'accPrivacy'), array('escape' => false,));?></li>	
								
						<?php /*?>		<li><a href=""><span class="icon icon-mynews"></span>Meine Zeitungen</a></li>
								<li><a href=""><span class="icon icon-articles"></span>Meine Artikel</a></li>
								<li><a href=""><span class="icon icon-mymessages"></span>Meine Nachrichten</a></li> <?php */?>
							</ul>
							
							<hr />		