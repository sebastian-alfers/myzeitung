<cake:nocache>
    <?php
    //we do not want the user to see the homee/index if logged in
    //a dirty hack to enable full page cache :/
    /*
    if($this->Session->read('Auth.User.username')){
        $url = $this->Html->url(array('controller' => 'users', 'action' => 'viewSubscriptions', 'username' => strtolower($this->Session->read('Auth.User.username')),'own_paper' => 'own'), true);
        header("Location: ".$url);
        exit;
    }
    */
    ?>
</cake:nocache>

<div class="start-header">
        <div id="header">
            <h1 id="logo"><a href="/">myZeitung</a></h1>
            <p class="slogan"><?php echo __('publishing together',true); ?> </p>



        <?php  echo $this->Form->create('User', array('id' => 'login-form', 'controller' => 'users', 'action' => 'login')); ?>
            <?php
            $locale = 'eng';
            if(!$this->Session->read('Config.language') || $this->Session->read('Config.language') == '' || $this->Session->read('Config.language') == 'deu') $locale = 'deu'; ?>
            <div style="float:left;margin-right:10px;height:40px;">
                <?php echo $this->element('locale/switch', array('locale' => $locale)); ?>
            </div>
        <?php  echo $this->Form->input('username', array('class' => 'textinput-login', 'div' => false,'label' => false)); ?>
        <?php  echo $this->Form->input('password', array('class' => 'textinput-login', 'div' => false, 'label' => false)); ?>
        <?php  echo $this->Form->button('Login', array('type' => 'submit' ,'class' => 'submit btn', 'div' => false, 'label' => false)); ?>
                <div class="remember">
        <?php  echo $this->Form->input('auto_login', array('type' => 'checkbox', 'class' => 'checkbox' , 'div' => false, 'label' => false, 'checked' => true)); ?>
                <span class="stay"><?php echo __('Remember Me', true);?>	</span>
                </div>

                <?php 	echo $this->Form->end(); ?>


                <div id="mainnav">

                    <form id="search" action="/search/" class="jqtransform">
                        <input name="q" id="inputString" autocomplete="off" class="searchinput" type="text" onblur="if (this.value == '') {this.value = '<?php echo __('Find', true);?>';}" onfocus="if (this.value == '<?php echo __('Find', true);?>') {this.value = '';}" value="<?php  __('Find'); ?>" />
                        <button class="submit" type="submit" value="">Suchen</button>
                        <ul id="search-suggest" style="display:none">
                        </ul><!-- end auto suggest -->
                    </form>
                </div>

        </div><!-- / #header -->
    </div>	<!-- /.start-header -->

    <div id="main-wrapper">

        <div id="content">

            <div id="maincolwrapper" class="onecol start">
                <div id="maincol">

                <div class="col1">
                    <h3><?php echo __('Top Papers', true);?></h3>
                    <?php echo $this->element('home/papers', array('cache' => '+10 minutes')); ?>
                    <div class="more">
                        <?php echo $this->Html->link(__('more papers', true), array('controller' => 'papers', 'action' => 'index')); ?>
                        </div>
                    <hr />

                    <h3><?php __('Top Authors'); ?></h3>
                        <?php echo $this->element('home/users', array('cache' => '+10 minutes')); ?>
                    <div class="more">
                        <?php echo $this->Html->link(__('more authors', true), array('controller' => 'users', 'action' => 'index')); ?>
                    </div>

                </div><!-- /.col1 -->

                <div class="col2">
                    <h3><?php echo __('New Articles',true);?></h3>
                        <?php echo $this->element('home/posts', array('cache' => '+10 minutes')); ?>
                    <div class="more">
                        <?php echo $this->Html->link(__('more posts', true), array('controller' => 'posts', 'action' => 'index')); ?>
                    </div>
                </div><!-- /.col2 -->

 					<div class="col3">
                        <h3> Du willst Nachrichten selbst veröffentlichen? Du willst deine eigene Onlinezeitung erstellen - eventuell mit deinen Bekannten?</h3>

                            <p>…und du suchst nach einem Netzwerk, dass dir all das bietet? Dann bist du hier richtig! Erstelle Artikel oder Zeitungen selbst, oder lies einfach die Inhalte anderer Autoren. Du kannst dich kostenlos und unverbindlich bei uns registrieren.</p>
                        <?php echo $this->Html->link('<span>+</span>'.__('Register', true), array('controller' => 'users',  'action' => 'add'), array('rel' => 'nofollow', 'escape' => false, 'class' => 'big btn', ));?>
                         <hr />
                        <h3>Wobei dir myZeitung helfen wird</h3>

						<?php echo $this->Html->image($cf->url('assets/pres_prev.jpg'), array('class' => 'mzslides'));?>

					</div><!-- /.col3 -->

                </div><!-- / #maincol -->

            </div><!-- / #maincolwrapper -->


<?php  // echo $this->element('search/autocomplete/script'); ?>
