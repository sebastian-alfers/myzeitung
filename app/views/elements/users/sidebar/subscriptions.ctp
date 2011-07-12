<?php



if(!isset($this->params['pass'][1]) || $this->params['pass'][1] == null){
    $this->params['pass'][1] = Paper::FILTER_ALL;
}


//order string for filter links
$filter_string = '';
if(isset($this->params['named']) && is_array($this->params['named'])){
    foreach($this->params['named'] as $key => $value){
        $filter_string .= "/".$key.":".$value;
    }
}


?>

<strong><?php  echo __('order by', true);?>:</strong>

<ul class="filter-search">

             <?php if(in_array($this->Paginator->sortKey(), array('Paper.title', 'Paper.title ASC', 'Paper.title DESC'))):?>
             <li class="active"><?php else:?><li><?php endif;?>
             <?php echo $this->Paginator->sort('<span class="icon icon-title"></span>'. __('Title', true), 'title', array('escape' => false)); ?></li>

             <?php if(in_array($this->Paginator->sortKey(), array('Paper.content_paper_count', 'Paper.content_paper_count ASC', 'Paper.content_paper_count DESC'))):?>
             <li class="active"><?php else:?><li><?php endif;?>
             <?php echo $this->Paginator->sort('<span class="icon icon-author"></span>'. __('Number of Authors', true), 'content_paper_count', array('escape' => false)); ?></li>

             <?php if(in_array($this->Paginator->sortKey(), array('Paper.category_paper_post_count', 'Paper.category_paper_post_count ASC', 'Paper.category_paper_post_count DESC'))):?>
             <li class="active"><?php else:?><li><?php endif;?>
             <?php echo $this->Paginator->sort('<span class="icon icon-article"></span>'. __('Number of Posts', true), 'category_paper_post_count', array('escape' => false)); ?></li>

             <?php if(in_array($this->Paginator->sortKey(), array('Paper.subscription_count', 'Paper.subscription_count ASC', 'Paper.subscription_count DESC'))):?>
             <li class="active"><?php else:?><li><?php endif;?>
             <?php echo $this->Paginator->sort('<span class="icon icon-subscription"></span>'. __('Number of Subscriptions', true), 'subscription_count', array('escape' => false)); ?></li>
</ul>



<hr />
    <ul class="filter-search">
        <?php if(isset($this->params['pass'][1]) && $this->params['pass'][1] == Paper::FILTER_ALL):?>
         <li class="active"><span class="icon icon-allresults"></span><?php echo __('All Papers', true);?> </li>
        <?php else:?>
            <li><?php echo $this->Html->link('<span class="icon icon-allresults"></span>'.__('All Papers', true), array('controller' => 'users',  'action' => 'viewSubscriptions', $this->params['pass'][0], Paper::FILTER_ALL, $filter_string),array('escape' => false));?></li>
        <?php endif;?>
        <?php if(isset($this->params['pass'][1]) && $this->params['pass'][1] == Paper::FILTER_OWN):?>
         <li class="active"><span class="icon icon-userresults"></span><?php echo $user['User']['username'].'\'s '.__('Papers', true);?></li>
        <?php else:?>
            <li><?php echo $this->Html->link('<span class="icon icon-userresults"></span>'.$user['User']['username'].'\'s '.__('Papers', true), array('controller' => 'users',  'action' => 'viewSubscriptions', $this->params['pass'][0], Paper::FILTER_OWN , $filter_string),array('escape' => false));?></li>
        <?php endif;?>
        <?php if(isset($this->params['pass'][1]) && $this->params['pass'][1] == Paper::FILTER_SUBSCRIBED):?>
         <li class="active"><span class="icon icon-newsresults"></span><?php echo $user['User']['username'].'\'s '.__('Subscriptions', true);?></li>
        <?php else:?>
            <li><?php echo $this->Html->link('<span class="icon icon-newsresults"></span>'.$user['User']['username'].'\'s '.__('Subscriptions', true), array('controller' => 'users',  'action' => 'viewSubscriptions', $this->params['pass'][0],  Paper::FILTER_SUBSCRIBED , $filter_string),array('escape' => false));?></li>
        <?php endif;?>
    </ul>

