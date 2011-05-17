<?php echo $this->element('users/sidebar'); ?>


<div id="maincolwrapper"> 
	<div id="maincol" class="account">
		<h4 class="account-title"><?php echo __('About Me', true);?></h4>

				<?php echo $this->Form->create('User');?>
			
				<p><label>Benutzername</label>
					<strong class="username">fourbai</strong>
				</p>
				
				<p class="optional info-p"><label for="data[User][name]" ><?php echo __('Name', true);?></label>
					<input id="UserName" class="textinput" type="text" maxlength="30" name="data[User][name]">
					<span class="info"><?php echo __('(optional) Makes sense if you want to be found by your name!')?></span>
				</p>
				
				<p class="optional info-p"><label for="data[User][description]" ><?php echo __('About Me', true);?></label>
					<textarea id="UserDescription" rows="2" cols="35" maxlength="300" name="data[User][description]"></textarea>
					<span class="info"><?php echo __('(optional) If you want to describe yourself.')?></span>
				</p>
			
				<div class="accept">

					<a class="btn big" ><span>+</span><?php echo __('Save Changes', true);?></a>
				</div>
				
			</form>			
	

	</div><!-- / #maincol -->

</div><!-- / #maincolwrapper -->