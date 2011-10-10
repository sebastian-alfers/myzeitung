<script type="text/javascript">
<!--
$(document).ready(function() {
	$("#link_add_user").click(function(){

			$('#register-form').submit();


	});	

});
//-->
</script>
				<div id="maincolwrapper" class="onecol"> 
					<div id="maincol">
						<div class="register">
							<div class="register-info">
								<h2><?php echo __('Create a new account', true);?></h2>
								<p><?php echo __('You already have an account?', true);?>&nbsp;<?php echo $this->Html->link(__('Click here',true), array('controller' => 'users', 'action' => 'login'));?></p>
							</div>
							<?php echo $this->Form->create('User', array('id' => 'register-form', 'class' => 'jqtransform', 'controller' => 'users', 'action' => 'add',
																		'inputDefaults' => array('error' => false, 'div' => false)));?>
							<div><?php  echo $this->Form->input('username', array('maxlength' => 15 ,'type' => 'text', 'class' => 'textinput', 'label' => __('Username', true))); ?>
							<?php if(!is_null($this->Form->error('User.username'))): ?>
								<div class="error-message"><b></b><?php echo $this->Form->error('User.username', array('wrap'=> false));?></div>
							<?php endif; ?>
							</div>
							
							<div class="optional info-p"><?php  echo $this->Form->input('name', array('maxlength' => 40, 'type' => 'text', 'class' => 'textinput', 'label' => __('Name', true))); ?>
							<span class="info"><?php echo __('(optional)', true);?>&nbsp;<?php echo __('If you want to be found by your name', true);?></span>
							<?php if(!is_null($this->Form->error('User.name'))): ?>
								<div class="error-message"><b></b><?php echo $this->Form->error('User.name', array('wrap'=> false));?></div>
							<?php endif; ?>
							</div>
							
							<div><?php  echo $this->Form->input('passwd', array('type' => 'password', 'class' => 'textinput', 'label' => __('Password', true))); ?>
							<?php if(!is_null($this->Form->error('User.passwd'))): ?>
								<div class="error-message"><b></b><?php echo $this->Form->error('User.passwd', array('wrap'=> false));?></div>
							<?php endif; ?>
							</div>
							
							<div class="info-p"><?php  echo $this->Form->input('email', array('type' => 'text', 'class' => 'textinput', 'label' => __('E-Mail', true))); ?>
                            <span class="info"><?php echo __('The email address is not visible to other users.', true);?></span>
							<?php if(!is_null($this->Form->error('User.email'))): ?>
								<div class="error-message"><b></b><?php echo $this->Form->error('User.email', array('wrap'=> false));?></div>
							<?php endif; ?>
							</div>
							
							
								<p class="agbs"><label for="agbs"><?php echo __('TOS', true);?></label>
								
								<textarea name="agbs" readonly="readonly"><?php echo strip_tags($this->element('global/agb')); ?></textarea>
								</p>

                            <div class="accept">
                                <div><?php  echo $this->Form->input('tos_accept', array('type' => 'checkbox', 'class' => 'textinput', 'label' => false)); ?><strong><?php echo __('I accept the',true )?>&nbsp;<?php echo $this->Html->link(__('TOS', true), array('controller' => 'pages', 'action' => 'display', 'agb'), array('rel' => 'nofollow', 'target' => '_blank'));?>&nbsp;<?php echo __('and', true)?> <?php echo $this->Html->link(__('Privacy Policy', true), array('controller' => 'pages', 'action' => 'display', 'dsr'), array('rel' => 'nofollow', 'target' => '_blank'));?></strong>
                                    <?php if(!is_null($this->Form->error('User.tos_accept'))): ?>
                                        <div class="error-message"><b></b><?php echo $this->Form->error('User.tos_accept', array('wrap'=> false));?></div>
                                        <?php endif; ?>
                                </div>
                                <a class="btn big" id="link_add_user"><span>+</span><?php echo __('Create Account', true);?></a>
                            </div>

                            <?php echo $this->Form->end(array('div' => false,'class' => 'hidden')); ?>

						</div> <!-- /register -->

					</div><!-- / #maincol -->
				
				</div><!-- / #maincolwrapper -->