<div id="leftcolwapper">
    <div class="leftcol">
	    <div class="leftcolcontent">
            <?php /*
            <form id="search-paper" action="search-result.html" class="">
                <input class="searchinput" type="text" onblur="if (this.value == '') {this.value = 'User durchsuchen';}" onfocus="if (this.value == 'User durchsuchen') {this.value = '';}" value="User durchsuchen" />
                <button class="submit" type="submit" value="">Suchen</button>
            </form>

            <hr />
                */ ?>
            <strong><?php echo __('order by', true).':';?></strong>

        <ul class="filter-search">
            <?php if(in_array($this->Paginator->sortKey(), array('User.content_paper_count', 'User.content_paper_count ASC', 'User.content_paper_count DESC'))):?>
             <li class="active"><?php else:?><li><?php endif;?>
             <?php echo $this->Paginator->sort('<span class="icon icon-subscription"></span>'. __('Number of Subscribers', true), 'content_paper_count', array('escape' => false,  'direction' => 'DESC')); ?></li>

            <?php if(in_array($this->Paginator->sortKey(), array('User.post_count', 'User.post_count ASC' , 'User.post_count DESC'))):?>
             <li class="active"><?php else:?><li><?php endif;?>
             <?php echo $this->Paginator->sort('<span class="icon icon-article"></span>'. __('Number of Posts', true), 'post_count', array('escape' => false,  'direction' => 'DESC')); ?></li>

            <?php if(in_array($this->Paginator->sortKey(), array('User.username' , 'User.username ASC' , 'User.username DESC'))):?>
             <li class="active"><?php else:?><li><?php endif;?>
             <?php echo $this->Paginator->sort('<span class="icon icon-title"></span>'. __('Username', true), 'username', array('escape' => false)); ?></li>

             <?php if(in_array($this->Paginator->sortKey(), array('User.created' , 'User.created ASC' , 'User.created DESC'))):?>
             <li class="active"><?php else:?><li><?php endif;?>
             <?php echo $this->Paginator->sort('<span class="icon icon-date"></span>'. __('Date of joining', true), 'created', array('escape' => false ,  'direction' => 'DESC')); ?></li>
        </ul>

<?php /*
            <ul class="filter-search">
                <li class="active"><a><span class="icon icon-subscription"></span>Anzahl Abonnenten</a></li>
                <li><a><span class="icon icon-author"></span>Geschrieben für Zeitungen</a></li>
                <li><a><span class="icon icon-article"></span>Anzahl Artikel</a></li>
                <li><a><span class="icon icon-title"></span>Name</a></li>
                <li><a><span class="icon icon-date"></span>Datum</a></li>

            </ul>
*/?>

         </div><!-- /.leftcolcontent -->
    </div><!-- /.leftcol -->

</div><!-- / #leftcolwapper -->