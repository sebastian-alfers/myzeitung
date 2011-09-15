<hr />
<?php
if($this->params['controller'] == 'users' && $this->params['action'] == 'viewSubscriptions'){
    $paginator->options(array('url'=> array('controller' => 'users',
                                            'action' => 'viewSubscriptions',
                                            'username' => strtolower($this->params['username'])) ,
                                                                                )

    );
} ?>

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
<?php if($this->params['controller'] == 'users' && $this->params['action'] == 'viewSubscriptions' ):?>
<strong><?php  echo __('order by', true);?>:</strong>

<ul class="filter-search">

             <?php if(in_array($this->Paginator->sortKey(), array('Paper.title', 'Paper.title ASC', 'Paper.title DESC'))):?>
             <li class="active"><?php else:?><li><?php endif;?>
             <?php echo $this->Paginator->sort('<span class="icon icon-title"></span>'. __('Title', true), 'title', array('escape' => false)); ?></li>

             <?php if(in_array($this->Paginator->sortKey(), array('Paper.author_count', 'Paper.author_count ASC', 'Paper.author_count DESC'))):?>
             <li class="active"><?php else:?><li><?php endif;?>
             <?php echo $this->Paginator->sort('<span class="icon icon-author"></span>'. __('Number of Authors', true), 'author_count', array('escape' => false, 'direction' => 'DESC')); ?></li>

             <?php if(in_array($this->Paginator->sortKey(), array('Paper.post_count', 'Paper.post_count ASC', 'Paper.post_count DESC'))):?>
             <li class="active"><?php else:?><li><?php endif;?>
             <?php echo $this->Paginator->sort('<span class="icon icon-article"></span>'. __('Number of Posts', true), 'post_count', array('escape' => false,  'direction' => 'DESC')); ?></li>

             <?php if(in_array($this->Paginator->sortKey(), array('Paper.subscription_count', 'Paper.subscription_count ASC', 'Paper.subscription_count DESC'))):?>
             <li class="active"><?php else:?><li><?php endif;?>
             <?php echo $this->Paginator->sort('<span class="icon icon-subscription"></span>'. __('Number of Subscriptions', true), 'subscription_count', array('escape' => false,  'direction' => 'DESC')); ?></li>
</ul>
<hr />
<?php endif;?>

    <?php $papersLinkText = sprintf(__('%s papers', true),$this->MzText->possessive($user['User']['username']));?>
    <?php $subscriptionsLinkText = sprintf(__('%s subscriptions', true),$this->MzText->possessive($user['User']['username']))?>

    <ul class="filter-search">
        <?php if($this->params['action'] == 'viewSubscriptions' && isset($this->params['pass'][1]) && $this->params['pass'][1] == Paper::FILTER_ALL):?>

         <li class="active"><span class="icon icon-allresults"></span><?php echo __('All Papers', true);?> </li>
        <?php else:?>
            <li><?php echo $this->Html->link('<span class="icon icon-allresults"></span>'.__('All Papers', true), array('controller' => 'users',  'action' => 'viewSubscriptions', 'username' => strtolower($user['User']['username']), Paper::FILTER_ALL, $filter_string),array('escape' => false));?></li>
        <?php endif;?>
        <?php if($this->params['action'] == 'viewSubscriptions' && isset($this->params['pass'][1]) && $this->params['pass'][1] == Paper::FILTER_OWN):?>
         <li class="active"><span class="icon icon-userresults"></span><?php echo $papersLinkText?></li>
        <?php else:?>
            <li><?php echo $this->Html->link('<span class="icon icon-userresults"></span>'.$papersLinkText, array('controller' => 'users',  'action' => 'viewSubscriptions', 'username' => strtolower($user['User']['username']), Paper::FILTER_OWN , $filter_string),array('escape' => false));?></li>
        <?php endif;?>
        <?php if($this->params['action'] == 'viewSubscriptions' && isset($this->params['pass'][1]) && $this->params['pass'][1] == Paper::FILTER_SUBSCRIBED):?>
         <li class="active"><span class="icon icon-newsresults"></span><?php echo $subscriptionsLinkText;?></li>
        <?php else:?>
            <li><?php echo $this->Html->link('<span class="icon icon-newsresults"></span>'.$subscriptionsLinkText, array('controller' => 'users',  'action' => 'viewSubscriptions', 'username' => strtolower($user['User']['username']),  Paper::FILTER_SUBSCRIBED , $filter_string),array('escape' => false));?></li>
        <?php endif;?>
    </ul>

