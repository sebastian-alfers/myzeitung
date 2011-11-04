<?php
$tw_url = '';
$fb_url = '';
$gp_url = '';
if(isset($url) && !empty($url)){
    $tw_url = 'data-url="'.$url.'"';
    $fb_url = 'data-href="'.$url.'"';
    $gp_url = 'href="'.$url.'"';
}

?>

<div id="social">
    <!-- twitter -->
    <ul>
    <li><a href="https://twitter.com/share" <?php echo $tw_url; ?> class="twitter-share-button" data-count="horizontal" data-via="myzeitung" data-lang="de">Tweet</a><script type="text/javascript" src="//platform.twitter.com/widgets.js"></script></li>
    <li>
    <!-- twitter end -->

    <!-- facebook -->
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {return;}
                        js = d.createElement(s); js.id = id;
                        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>

    <div class="fb-like" <?php echo $fb_url; ?> data-send="false" data-width="50" data-show-faces="false" layout="button_count" style="z-index: 4000;"></div>
    </li>
    <!-- facebook end -->

    <!-- g+ -->
    <li>
    <g:plusone <?php echo $gp_url; ?> size="medium"></g:plusone>
    </li>
    <!-- g+ end -->
</div>