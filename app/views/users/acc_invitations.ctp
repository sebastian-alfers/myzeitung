<?php echo $this->element('users/sidebar'); ?>
    <div id="maincolwrapper">
        <div id="maincol" class="account invitations-overview ">
            <h2 class="account-title"><?php echo __('Invitations', true);?></h2>



            <ul class="invitations">

                <?php foreach($invitations as $invitation):?>
                <li class="invitation">
                    <ul>

                        <li class="invitation-info">
                            <h5><?php echo $invitation['Invitation']['text'];?></h5>
                        </li>
                        <li class="actions">
                            <?php echo $this->Html->link('',array('controller' => 'invitations', 'action' => 'delete', $invitation['Invitation']['id']), array('class' => 'icon icon-delete', 'escape' => false));?>
                            <p><?php echo $this->MzTime->timeAgoInWords($invitation['Invitation']['created'], array('format' => 'd.m.y  h:m','end' => '+1 Month'));?></p>
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
