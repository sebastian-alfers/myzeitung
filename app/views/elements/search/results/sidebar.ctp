<div id="leftcolwapper">
<div class="leftcol">
	<div class="leftcolcontent">
			<ul class="filter-search">
				<?php // all results?>
			    <?php if($this->params['controller'] == 'search' && $this->params['action'] == 'index'):?><li class="active"><?php else:?><li><?php endif;?>
    			<?php echo $this->Html->link('<span class="icon icon-allresults"></span>'.__('All Results', true), array('controller' => 'search', 'action' => 'index'), array('escape' => false,));?></li>
    			<?php // just users?>
				<?php if($this->params['controller'] == 'search' && $this->params['action'] == 'users'):?><li class="active"><?php else:?><li><?php endif;?>
    			<?php echo $this->Html->link('<span class="icon icon-userresults"></span>'.__('Users', true), array('controller' => 'search', 'action' => 'users'), array('escape' => false,));?></li>
    			<?php // just papers?>
    			<?php if($this->params['controller'] == 'search' && $this->params['action'] == 'papers'):?><li class="active"><?php else:?><li><?php endif;?>
    			<?php echo $this->Html->link('<span class="icon icon-newsresults"></span>'.__('Papers', true), array('controller' => 'search', 'action' => 'papers'), array('escape' => false,));?></li>
    			<?php // just posts?>
    			<?php if($this->params['controller'] == 'search' && $this->params['action'] == 'posts'):?><li class="active"><?php else:?><li><?php endif;?>
    			<?php echo $this->Html->link('<span class="icon icon-articleresults"></span>'.__('Posts', true), array('controller' => 'search', 'action' => 'posts'), array('escape' => false,));?></li>		

			</ul>
			
		 </div><!-- /.leftcolcontent -->	
		</div><!-- /.leftcol -->
		
</div><!-- / #leftcolwapper -->
