<?php echo $this->element('rss/modal_delete_feed'); ?>
<?php echo $this->element('users/sidebar'); ?>
<div id="maincolwrapper">
    <div id="maincol" class="account invitations-overview">
        <div class="account-nav">
            <h2 class="account-title"><?php echo __('RSS Feeds', true);?></h2>

            <ul class="create-actions">

                <li class="big-btn">
                    <div class="invitation">
                        <a class="btn invitation-btn" href="#" id=""><span>+</span><?php __('Import new Rss Feed'); ?>
                        </a>
                    </div>
                </li>
            </ul>
            <p>
                <?php __('Here you can import your Rss Feeds. Once you added a Rss Feed, it will imported regularly.'); ?>
            </p>

            <p>
                <?php __('The contents of you Feeds will be published as you would create regular posts.'); ?>
            </p>
        </div>


        <ul class="invitations">

            <?php foreach ($feeds as $feed): ?>
            <ul class="invitations">

                <li class="invitation">
                    <ul>
                        <li class="invitation-info">
                            <span class="icon rss-icon-white"></span>
                            <span>
                                <?php echo $this->Html->link($feed['RssFeed']['url'], $feed['RssFeed']['url'], array('target' => '_blank', 'class' => 'rss-url'));?>
                            </span>
                        </li>
                        <li class="actions">
                            <a class="icon icon-delete delete-rss" id="<?php echo $feed['RssFeedsUser']['id']; ?>"></a>
                        </li>
                        <li class="invitee">
                            <ul>
                                <li class="single-invitee">
                                    <ul class="invitee-info">
                                        <li class="rssimport">
                                            <?php if (isset($feed['RssFeed']['RssImportLog'][0]['created']) && !empty($feed['RssFeed']['RssImportLog'][0]['created'])): ?>
                                            <?php __('Last Import'); ?>
                                            : <?php echo $this->MzTime->timeAgoInWords($feed['RssFeed']['RssImportLog'][0]['created'], array('format' => 'd.m.y  h:m', 'end' => '+1 Month')); ?>
                                            <?php else: ?>
                                            <?php __('Waiting'); ?>
                                            <?php endif; ?>
                                        </li>

                                        <li class="status"></li>
                                        <li class="info"></li>


                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>

                </li>
                <!-- /.invitation-->

            </ul>


            <?php endforeach;?>

        </ul>

        <div class="pagination">
            <?php echo $this->element('global/paginate'); ?>
        </div>


    </div>
    <!-- / #maincol -->


</div><!-- / #maincolwrapper -->
