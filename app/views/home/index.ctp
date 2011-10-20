<cake:nocache>
    <?php
    //we do not want the user to see the homee/index if logged in
    //a dirty hack to enable full page cache :/
    if($this->Session->read('Auth.User.username')){
        $url = $this->Html->url(array('controller' => 'users', 'action' => 'viewSubscriptions', 'username' => strtolower($this->Session->read('Auth.User.username')),'own_paper' => 'own'), true);
        header("Location: ".$url);
        exit;
    }
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
                        <ul>

                        <?php foreach($papers as $paper):?>
                            <li>
                                <?php
                                $link_data = array();
                                $link_data['url'] = $paper['Route'][0]['source'];
                                $link_data['custom'] = array('class' => 'tt-title', 'title' => $paper['Paper']['title']);
                                $img['image'] = $paper['Paper']['image'];
                                echo $image->render($img, 58, 58, array("alt" => $paper['Paper']['title']), $link_data, ImageHelper::PAPER);
                                ?>
                            </li>

                        <?php endforeach;?>
                        </ul>

                    <div class="more">
                        <?php echo $this->Html->link(__('more papers', true), array('controller' => 'papers', 'action' => 'index')); ?>
                        </div>
                    <hr />

                    <h3><?php __('Top Authors'); ?></h3>
                        <ul>

                        <?php foreach($users as $user):?>
                            <li>
                                <?php
                                $tipsy_name= $this->MzText->generateDisplayname($user['User']);
                                $link_data = array();
                                $link_data['url'] = array('controller' => 'users', 'action' => 'view', 'username' => strtolower($user['User']['username']));
                                $link_data['custom'] = array('class' => 'user-image tt-title', 'title' => $tipsy_name);
                                echo $image->render($user['User'], 58, 58, array("alt" => $user['User']['username']), $link_data);
                                ?>
                                <?php /*<span><?php echo $this->Html->link($user['User']['username'], array('controller' => 'users', 'action' => 'view', $user['User']['id']));?></span>*/?>
                            </li>
                        <?php endforeach;?>
                    </ul>
                    <div class="more">
                        <?php echo $this->Html->link(__('more authors', true), array('controller' => 'users', 'action' => 'index')); ?>
                    </div>

                </div><!-- /.col1 -->

                <div class="col2">
                    <h3><?php echo __('New Articles',true);?></h3>

                        <?php foreach($posts as $post):?>

                            <div class="article">
                                <?php // post headline
                                    $headline = substr($post['Post']['title'],0,50);
                                    if(strlen($post['Post']['title']) > 50){
                                        $headline .='...';
                                    }
                                ?>
                                <?php echo $this->Html->link($headline, $post['Post']['Route'][0]['source']);?>
                                <?php // user container?>
                                 <?php //debug($post); die(); ?>
                                <?php $authorName1 = $this->MzText->generateDisplayname($post['Post']['User'],false);
                                      $authorName2 = '';
                                      if(isset($post['Post']['User']['name']) && !empty($post['Post']['User']['name'])){
                                         $authorName2 = $post['Post']['User']['username'];
                                      }
                                ?>
                                 <?php echo $this->Html->link(
                                        $image->render($post['Post']['User'], 26, 26, array( "alt" => $post['Post']['User']['username'], "class" => 'user-image'), array("tag" => "div"), ImageHelper::USER)
                                        .'<span>'.$authorName1.'<br />'.$authorName2.'</span>',
                                            array('controller' => 'users', 'action' => 'view', 'username' => strtolower($post['Post']['User']['username'])),
                                            array('class' => "user",'escape' => false));?>


                            </div>
                        <?php endforeach;?>
                    <div class="more">
                        <?php echo $this->Html->link(__('more posts', true), array('controller' => 'posts', 'action' => 'index')); ?>

                    </div>
                </div><!-- /.col2 -->

 					<div class="col3">
                        <h3> Du willst Nachrichten selbst veröffentlichen? Du willst deine eigene Onlinezeitung erstellen - eventuell mit deinen Bekannten?</h3>

                            <p>…und du suchst nach einem Netzwerk, dass dir all das bietet? Dann bist du hier richtig! Erstelle Artikel oder Zeitungen selbst, oder lies einfach die Inhalte anderer Autoren. Du kannst dich kostenlos und unverbindlich bei uns registrieren.</p>
                        <?php echo $this->Html->link('<span>+</span>'.__('Register', true), array('controller' => 'users',  'action' => 'add'), array('rel' => 'nofollow', 'escape' => false, 'class' => 'big btn', ));?>
                         <hr />
                        <h3>Lorem ipsum dolor sit amet, consetetur sadipscing elitr</h3>

						<?php echo $this->Html->image('../img/assets/pres_prev.jpg', array('class' => 'mzslides'));?>

					</div><!-- /.col3 -->

                </div><!-- / #maincol -->

            </div><!-- / #maincolwrapper -->


<?php  // echo $this->element('search/autocomplete/script'); ?>
