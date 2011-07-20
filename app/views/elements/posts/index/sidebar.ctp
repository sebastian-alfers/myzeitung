
 
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
          
             <?php if(in_array($this->Paginator->sortKey(), array('Post.created', 'Post.created ASC', 'Post.created DESC'))):?>
             <li class="active"><?php else:?><li><?php endif;?>
             <?php echo $this->Paginator->sort('<span class="icon icon-date"></span>'. __('Date', true), 'created', array('escape' => false ,  'direction' => 'DESC')); ?></li>

             <?php if(in_array($this->Paginator->sortKey(), array('Post.view_count', 'Post.view_count ASC', 'Post.view_count DESC'))):?>
             <li class="active"><?php else:?><li><?php endif;?>
             <?php echo $this->Paginator->sort('<span class="icon icon-subscription"></span>'. __('Number of views', true), 'view_count', array('escape' => false ,  'direction' => 'DESC')); ?></li>

             <?php if(in_array($this->Paginator->sortKey(), array('Post.comment_count', 'Post.comment_count ASC', 'Post.comment_count DESC'))):?>
             <li class="active"><?php else:?><li><?php endif;?>
             <?php echo $this->Paginator->sort('<span class="icon icon-author"></span>'. __('Number of comments', true), 'comment_count', array('escape' => false ,  'direction' => 'DESC')); ?></li>

             <?php if(in_array($this->Paginator->sortKey(), array('Post.posts_user_count', 'Post.posts_user_count ASC', 'Post.posts_user_count DESC'))):?>
             <li class="active"><?php else:?><li><?php endif;?>
             <?php echo $this->Paginator->sort('<span class="icon icon-repost"></span>'. __('Number of reposts', true), 'posts_user_count', array('escape' => false ,  'direction' => 'DESC')); ?></li>

             <?php if(in_array($this->Paginator->sortKey(), array('Post.title', 'Post.title ASC', 'Post.title DESC'))):?>
             <li class="active"><?php else:?><li><?php endif;?>
             <?php echo $this->Paginator->sort('<span class="icon icon-title"></span>'. __('Title', true), 'title', array('escape' => false)); ?></li>

        </ul>

         </div><!-- /.leftcolcontent -->
        </div><!-- /.leftcol -->

</div><!-- / #leftcolwapper -->