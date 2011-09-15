<?php echo $this->element('users/sidebar'); ?>
    <div id="maincolwrapper">
        <div id="maincol" class="account invitations-overview">
            <div class="account-nav">
                <h2 class="account-title"><?php echo __('Invitations', true);?></h2>

                <ul class="create-actions">

                    <li class="big-btn"><?php echo $this->element('invite/button'); ?></li>
                </ul>
            </div>


            <ul class="invitations">

                <?php foreach($invitations as $invitation):?>
                <li class="invitation">
                    <ul>

                        <li class="invitation-info">
                            <?php if(!empty($invitation['Invitation']['text'])):?>
                                <h5><?php echo $invitation['Invitation']['text'];?></h5>
                            <?php else:?>
                                <h5><?php echo __('no personal text sent',true);?></h5>
                            <?php endif;?>
                        </li>
                        <li class="actions">
                            <?php echo $this->Html->link('',array('controller' => 'invitations', 'action' => 'delete', $invitation['Invitation']['id']), array('class' => 'icon icon-delete', 'escape' => false),__('Are you sure you want to delete this Invitation-list?', true));?>
                            <p><?php echo $this->MzTime->timeAgoInWords($invitation['Invitation']['created'], array('format' => 'd.m.y  h:m','end' => '+1 Month'));?></p>
                        </li>
                         <li class="invitee">
                            <ul>
                                <?php foreach($invitation['Invitee'] as $invitee):?>
                                    <li class="single-invitee">
                                        <ul class="invitee-info">
                                            <li class="email"><?php echo $invitee['email'];?></li>

                                                <?php /* if(isset($invitee['User']['id'])):?>
                                                 <li class="status registered"><?php echo __('already registered',true);?></li>
                                                 <li class="info"><?php echo __('Username', true).': '.$this->Html->link($invitee['User']['username'], array('controller' => 'users','action' => 'view', 'username' => strtolower($invitee['User']['username'])));?></li>
                                                <li class="buttons"><a href="#" class="btn subscribe-user" id="<?php echo $invitee['User']['id']; ?>"><span>+</span><?php __('Subscribe Author'); ?></a></li>
                                                <?php else:  ?>
                                                 <li class="status not-registered"><?php echo __('not yet registered',true);?></li> <?php */ ?>
                                                 <li class="status"></li>
                                                 <li class="info"><?php if($invitee['reminder_count'] >0) echo sprintf(__n('%d time reminded','%d times reminded',$invitee['reminder_count'] ,true), $invitee['reminder_count']);?></li>
                                                 <li class="buttons"><?php echo $this->Html->link(__('Send Reminder',true),array('controller' => 'invitations', 'action' => 'remindInvitee', $invitee['id']), null,sprintf(__('Are you sure to send a reminder email to: %s?', true),$invitee['email'])); ?></li>
                                                <?php /* endif; */?>



                                        </ul>
                                    </li>
                                <?php endforeach;?>
                            </ul>
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
