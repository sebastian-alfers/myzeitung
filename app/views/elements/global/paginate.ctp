<?php
//custom paginate link-options for specific actions

//post navigator
//  user profile
if($this->params['controller'] == 'users' && $this->params['action'] == 'view'){
    $options = array('url'=> array('controller' => 'users',
                                            'action' => 'view',
                                            'username' => $this->params['username'])                                                                             
    );
        if(isset($this->params['topic_id'])){
        $options['url']['topic_id'] = $this->params['topic_id'];
    }
    $paginator->options($options);
}

//  paper view
if($this->params['controller'] == 'papers' && $this->params['action'] == 'view'){
    $options = array();
    if($paper['Paper']['premium_route'] != null && !empty($paper['Paper']['premium_route'] ) ){
        $options['url'] = array('controller' => 'papers',
                                            'action' => 'view',
                                            $paper['Paper']['id']);
    }else{
        $options['url'] = array('controller' => 'papers',
                                            'action' => 'view',
                                            $paper['Paper']['id'],
                                            'username' => $this->params['username'],
                                            'slug' => $this->params['slug']);
    }
   
    if(isset($this->params['category_id'])){
        $options['url']['category_id'] = $this->params['category_id'];
    }
    $paginator->options($options);

}
//papers navigator
//  users papers / view subscriptions

// MOVED TO elements/users/sidebar/subscriptions  ---> it is also necessary for paginate-order links


?>

    <div class="pagination">
        <?php if($this->Paginator->hasPrev()): ?>
            <?php echo $this->Paginator->prev(__('Previous', true), null, null, array('class' => 'disabled')); ?>
            <?php $this->set('paginator_prev_for_layout', $this->Html->url($this->Paginator->url(array('page' => $this->Paginator->current()-1)),true));?>
        <?php endif;?>
        <?php echo $this->Paginator->numbers(array('separator' => '')); ?>
        <?php if($this->Paginator->hasNext()): ?>
             <?php echo $this->Paginator->next(__('Next', true), null, null, array('class' => 'disabled')); ?>
             <?php $this->set('paginator_next_for_layout', $this->Html->url($this->Paginator->url(array('page' => $this->Paginator->current()+1)),true));?>
        <?php endif; ?>
    </div>
