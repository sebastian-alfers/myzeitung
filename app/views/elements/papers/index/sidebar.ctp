<div id="leftcolwapper">
<div class="leftcol">
    <div class="leftcolcontent">
        <?php /*
        <form id="search-paper" action="search-result.html" class="">
            <input class="searchinput" type="text" onblur="if (this.value == '') {this.value = 'Suchen';}" onfocus="if (this.value == 'Suchen') {this.value = '';}" value="Suchen" />
            <button class="submit" type="submit" value="">Suchen</button>
        </form>
        <hr />
        */ ?>

        <strong><?php  echo __('order by', true);?>:</strong>

        <ul class="filter-search">

            <?php if(!isset($this->params['pass'][0])):?>
			   <?php /* active - no link */ ?><li class="active"><span class="icon icon-title"></span><?php echo __('Title', true);?></li>
			<?php else:?>
				<?php /* not active - show link*/ ?> <li><?php echo $this->Html->link('<span class="icon icon-title"></span>'.__('Title', true),array('controller' => 'papers', 'action' => 'index'), array('escape' => false));?></li>
			<?php endif;?> </li>

            <?php if(isset($this->params['pass'][0]) && $this->params['pass'][0] == Paper::ORDER_AUTHORS_COUNT ):?>
			   <?php /* active - no link */ ?><li class="active"><span class="icon icon-author"></span><?php echo __('Number of Authors', true);?></li>
			<?php else:?>
				<?php /* not active - show link*/ ?> <li><?php echo $this->Html->link('<span class="icon icon-author"></span>'.__('Number of Authors', true),array('controller' => 'papers', 'action' => 'index', Paper::ORDER_AUTHORS_COUNT), array('escape' => false));?></li>
			<?php endif;?> </li>

            <?php if(isset($this->params['pass'][0]) && $this->params['pass'][0] == Paper::ORDER_ARTICLE_COUNT ):?>
			   <?php /* active - no link */ ?><li class="active"><span class="icon icon-article"></span><?php echo __('Number of Articles', true);?></li>
			<?php else:?>
				<?php /* not active - show link*/ ?> <li><?php echo $this->Html->link('<span class="icon icon-article"></span>'.__('Number of Articles', true),array('controller' => 'papers', 'action' => 'index', Paper::ORDER_ARTICLE_COUNT), array('escape' => false));?></li>
			<?php endif;?> </li>

            <?php if(isset($this->params['pass'][0]) && $this->params['pass'][0] == Paper::ORDER_SUBSCRIPTION_COUNT ):?>
			   <?php /* active - no link */ ?><li class="active"><span class="icon icon-subscription"></span><?php echo __('Number of Subscriptions', true);?></li>
			<?php else:?>
				<?php /* not active - show link*/ ?> <li><?php echo $this->Html->link('<span class="icon icon-subscription"></span>'.__('Number of Subscriptions', true),array('controller' => 'papers', 'action' => 'index', Paper::ORDER_SUBSCRIPTION_COUNT), array('escape' => false));?></li>
			<?php endif;?> </li>

        </ul>

         </div><!-- /.leftcolcontent -->
        </div><!-- /.leftcol -->

</div><!-- / #leftcolwapper -->