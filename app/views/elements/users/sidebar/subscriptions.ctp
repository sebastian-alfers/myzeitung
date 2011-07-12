<?php


if(!isset($this->params['pass'][2]) || $this->params['pass'][2] == null){
    $this->params['pass'][2] = Paper::FILTER_ALL;
}
if(!isset($this->params['pass'][1]) || $this->params['pass'][1] == null ){
    $this->params['pass'][1] = Paper::ORDER_TITLE;
}

?>

<strong><?php  echo __('order by', true);?>:</strong>

<ul class="filter-search">

    <?php if(isset($this->params['pass'][1]) && $this->params['pass'][1] == Paper::ORDER_TITLE):?>
       <?php /* active - no link */ ?><li class="active"><span class="icon icon-title"></span><?php echo __('Title', true);?></li>
    <?php else:?>
        <?php /* not active - show link*/ ?> <li><?php echo $this->Html->link('<span class="icon icon-title"></span>'.__('Title', true),array('controller' => 'users', 'action' => 'viewSubscriptions',  $this->params['pass'][0], Paper::ORDER_TITLE,  $this->params['pass'][2] ), array('escape' => false));?></li>
    <?php endif;?> </li>

    <?php if(isset($this->params['pass'][1]) && $this->params['pass'][1] == Paper::ORDER_AUTHORS_COUNT ):?>
       <?php /* active - no link */ ?><li class="active"><span class="icon icon-author"></span><?php echo __('Number of Authors', true);?></li>
    <?php else:?>
        <?php /* not active - show link*/ ?> <li><?php echo $this->Html->link('<span class="icon icon-author"></span>'.__('Number of Authors', true),array('controller' => 'users', 'action' => 'viewSubscriptions', $this->params['pass'][0], Paper::ORDER_AUTHORS_COUNT,  $this->params['pass'][2]), array('escape' => false));?></li>
    <?php endif;?> </li>

    <?php if(isset($this->params['pass'][1]) && $this->params['pass'][1] == Paper::ORDER_ARTICLE_COUNT ):?>
       <?php /* active - no link */ ?><li class="active"><span class="icon icon-article"></span><?php echo __('Number of Articles', true);?></li>
    <?php else:?>
        <?php /* not active - show link*/ ?> <li><?php echo $this->Html->link('<span class="icon icon-article"></span>'.__('Number of Articles', true),array('controller' => 'users', 'action' => 'viewSubscriptions',  $this->params['pass'][0], Paper::ORDER_ARTICLE_COUNT , $this->params['pass'][2]), array('escape' => false));?></li>
    <?php endif;?> </li>

    <?php if(isset($this->params['pass'][1]) && $this->params['pass'][1] == Paper::ORDER_SUBSCRIPTION_COUNT ):?>
       <?php /* active - no link */ ?><li class="active"><span class="icon icon-subscription"></span><?php echo __('Number of Subscriptions', true);?></li>
    <?php else:?>
        <?php /* not active - show link*/ ?> <li><?php echo $this->Html->link('<span class="icon icon-subscription"></span>'.__('Number of Subscriptions', true),array('controller' => 'users', 'action' => 'viewSubscriptions',  $this->params['pass'][0], Paper::ORDER_SUBSCRIPTION_COUNT,  $this->params['pass'][2]), array('escape' => false));?></li>
    <?php endif;?> </li>
</ul>

<hr />
    <ul class="filter-search">
        <?php if(isset($this->params['pass'][2]) && $this->params['pass'][2] == Paper::FILTER_ALL):?>
         <li class="active"><span class="icon icon-allresults"></span><?php echo __('All Papers', true);?> </li>
        <?php else:?>
            <li><?php echo $this->Html->link('<span class="icon icon-allresults"></span>'.__('All Papers', true), array('controller' => 'users',  'action' => 'viewSubscriptions', $this->params['pass'][0],$this->params['pass'][1], Paper::FILTER_ALL),array('escape' => false));?></li>
        <?php endif;?>
        <?php if(isset($this->params['pass'][2]) && $this->params['pass'][2] == Paper::FILTER_OWN):?>
         <li class="active"><span class="icon icon-userresults"></span><?php echo $user['User']['username'].'\'s '.__('Papers', true);?></li>
        <?php else:?>
            <li><?php echo $this->Html->link('<span class="icon icon-userresults"></span>'.$user['User']['username'].'\'s '.__('Papers', true), array('controller' => 'users',  'action' => 'viewSubscriptions', $this->params['pass'][0],$this->params['pass'][1], Paper::FILTER_OWN),array('escape' => false));?></li>
        <?php endif;?>
        <?php if(isset($this->params['pass'][2]) && $this->params['pass'][2] == Paper::FILTER_SUBSCRIBED):?>
         <li class="active"><span class="icon icon-newsresults"></span><?php echo $user['User']['username'].'\'s '.__('Subscriptions', true);?></li>
        <?php else:?>
            <li><?php echo $this->Html->link('<span class="icon icon-newsresults"></span>'.$user['User']['username'].'\'s '.__('Subscriptions', true), array('controller' => 'users',  'action' => 'viewSubscriptions', $this->params['pass'][0],$this->params['pass'][1],  Paper::FILTER_SUBSCRIBED),array('escape' => false));?></li>
        <?php endif;?>
    </ul>

