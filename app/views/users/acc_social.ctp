<script type="text/javascript">
<!--
$(document).ready(function() {
	$('.toggle_twitter').click(function(){
        var options = {};
        $('.twitter').toggle( 'blind', options, 500 );
        timeout = setTimeout(function() {
                document.location = '<?php echo $tw_toggle_url; ?>';
        }, 200);

    });

	$('.toggle_fb').click(function(){
        var options = {};
        $('.fb').toggle( 'blind', options, 500 );
        timeout = setTimeout(function() {
                document.location = '<?php echo $fb_url; ?>';
        }, 200);

    });
});
//-->
</script>
<?php echo $this->element('users/sidebar'); ?>

<div id="maincolwrapper">
	<div id="maincol" class="account">
		<h2 class="account-title"><?php echo __('Social Media', true);?></h2>
        <p><strong><?php echo __('Connect with your social media profiles, to spread your message even more. Your new articles will be promoted automatically on the connected networks.',true); ?></strong> </p>

        <?php echo $this->Form->create('User', array('class' => 'jqtransform',  'inputDefaults' => array('error' => false, 'div' => false)));?>
        <?php echo $this->Form->hidden('id' , array('value' => $user['User']['id']));?>
        <?php echo $this->Form->hidden('username' , array('value' => $user['User']['username']));?>
        <?php echo $this->Form->hidden('image' , array('value' => $user['User']['image']));?>
        <div class="accept">


            <p><?php  echo $this->Form->input('use_twitter', array('type' => 'checkbox', 'class' => 'toggle_twitter textinput','label' => false)); ?><strong><?php echo __('Twitter', true);?></strong></p>
            <?php if($this->data['User']['use_twitter'] && isset($this->data['User']['twitter_account_data'])): ?>
                <div class="social-account twitter">
                    <div class="img">
                        <img src="<?php  echo $this->data['User']['twitter_account_data']['profile_image_url']; ?>" />
                    </div>
                    <div>
                        <?php if($this->data['User']['twitter_account_data']['screen_name'] && isset($this->data['User']['twitter_account_data']['screen_name'])): ?>
                            <a href="http://twitter.com/#!/<?php echo $this->data['User']['twitter_account_data']['screen_name']; ?>" target="_blank">@<?php echo $this->data['User']['twitter_account_data']['screen_name']; ?></a>
                        <?php endif; ?>
                        <?php if($this->data['User']['twitter_account_data']['name'] && isset($this->data['User']['twitter_account_data']['name'])): ?>
                            <br /><?php echo $this->data['User']['twitter_account_data']['name']; ?>
                        <?php endif; ?>
                    </div>
                    <div>
                        <a class="btn toggle_twitter"><span>-</span><?php __('Disconnect Twitter'); ?></a>
                    </div>
                </div>
            <?php else: ?>
                <div style="display: none;padding-left:20px;" class="twitter">
                    <img src="/img/assets/loader.gif" />
                </div>
            <?php endif; ?>

            <div class="clear"></div>

            <p><?php  echo $this->Form->input('use_fb', array('type' => 'checkbox', 'class' => 'toggle_fb textinput','label' => false)); ?><strong><?php echo __('Facebook', true);?></strong></p>
            <?php if($this->data['User']['use_fb'] && isset($this->data['User']['fb_account_data'])): ?>
            <div class="social-account fb">
                <div class="img">
                    <img src="https://graph.facebook.com/<?php echo $fb_user; ?>/picture">
                </div>
                <div>
                    <?php if($this->data['User']['fb_account_data']['name'] != '' && isset($this->data['User']['fb_account_data']['name']) && $this->data['User']['fb_account_data']['link'] != '' && isset($this->data['User']['fb_account_data']['link'])): ?>
                        <a href="<?php echo $this->data['User']['fb_account_data']['link']; ?>" target="_blank"><?php echo $this->data['User']['fb_account_data']['name']; ?></a>
                    <?php endif; ?>
                </div>
                    <div>
                        <a class="btn toggle_fb"><span>-</span><?php __('Disconnect Facebook'); ?></a>
                    </div>
            </div>
            <?php else: ?>
                <div style="display: none;padding-left:20px;" class="fb">
                    <img src="/img/assets/loader.gif" />
                </div>
            <?php endif; ?>
        </div>
        <?php echo $this->Form->end(array('div' => false,'class' => 'hidden')); ?>
    </div><!-- / #maincol -->
</div><!-- / #maincolwrapper -->