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


             <?php if(in_array($this->Paginator->sortKey(), array('Paper.title', 'Paper.title ASC', 'Paper.title DESC'))):?>
             <li class="active"><?php else:?><li><?php endif;?>
             <?php echo $this->Paginator->sort('<span class="icon icon-title"></span>'. __('Title', true), 'title', array('escape' => false)); ?></li>

             <?php if(in_array($this->Paginator->sortKey(), array('Paper.author_count', 'Paper.author_count ASC', 'Paper.author_count DESC'))):?>
             <li class="active"><?php else:?><li><?php endif;?>
             <?php echo $this->Paginator->sort('<span class="icon icon-author"></span>'. __('Number of Authors', true), 'author_count', array('escape' => false  ,  'direction' => 'DESC')); ?></li>

             <?php if(in_array($this->Paginator->sortKey(), array('Paper.post_count', 'Paper.post_count ASC', 'Paper.post_count DESC'))):?>
             <li class="active"><?php else:?><li><?php endif;?>
             <?php echo $this->Paginator->sort('<span class="icon icon-article"></span>'. __('Number of Posts', true), 'post_count', array('escape' => false ,  'direction' => 'DESC')); ?></li>

             <?php if(in_array($this->Paginator->sortKey(), array('Paper.subscription_count', 'Paper.subscription_count ASC', 'Paper.subscription_count DESC'))):?>
             <li class="active"><?php else:?><li><?php endif;?>
             <?php echo $this->Paginator->sort('<span class="icon icon-subscription"></span>'. __('Number of Subscriptions', true), 'subscription_count', array('escape' => false ,  'direction' => 'DESC')); ?></li>


        </ul>

         </div><!-- /.leftcolcontent -->
        </div><!-- /.leftcol -->

</div><!-- / #leftcolwapper -->