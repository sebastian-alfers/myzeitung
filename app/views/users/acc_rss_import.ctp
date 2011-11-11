<?php echo $this->element('users/sidebar'); ?>
    <div id="maincolwrapper">
        <div id="maincol" class="account rss-feed-import-overview">
            <div class="account-nav">
                <h2 class="account-title"><?php echo __('RSS-Import', true);?></h2>

                <ul class="create-actions">


                </ul>
            </div>


            <ul class="feeds">

                <?php foreach($feeds as $feed):?>
                <li class="feed">
                    <ul>

                        <li class="feed-url">
                            <?php echo $feed['RssFeed']['url']?>
                        </li>
                        <li class="feed-update"> letztes update 12.34.56 <?php //echo $this->MzTime->timeAgoInWords($invitation['Invitation']['created'], array('format' => 'd.m.y  h:m','end' => '+1 Month'));?> </li>
                        <li class="actions">
                            <?php // echo $this->Html->link('',array('controller' => 'invitations', 'action' => 'delete', $invitation['Invitation']['id']), array('class' => 'icon icon-delete', 'escape' => false),__('Are you sure you want to delete this Invitation-list?', true));?>
                        </li>

                    </ul>

                </li><!-- /.invitation-->
                <?php endforeach;?>

            </ul>

            <div class="pagination">
                 <?php echo $this->element('global/paginate'); ?>
            </div>



        </div><!-- / #maincol -->

    </div><!-- / #maincolwrapper -->
