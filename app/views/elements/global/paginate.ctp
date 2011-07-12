    <div class="pagination">
        <?php if($this->Paginator->hasPrev()): ?>
        <?php echo $this->Paginator->prev(__('Previous', true), null, null, array('class' => 'disabled')); ?>
        <?php endif;?>
        <?php echo $this->Paginator->numbers(array('separator' => '')); ?>
        <?php if($this->Paginator->hasNext()): ?>
        <?php echo $this->Paginator->next(__('Next', true), null, null, array('class' => 'disabled')); ?>
        <?php endif; ?>
    </div><?php
