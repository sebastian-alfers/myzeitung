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
         <?php echo $this->Html->link(
                $image->render($post['Post']['User'], 26, 26, array( "alt" => $post['Post']['User']['username'], "class" => 'user-image'), array("tag" => "div"), ImageHelper::USER)
                .'<span>'.$this->MzText->generateDisplayname($post['Post']['User']).'</span>',
                    array('controller' => 'users', 'action' => 'view', 'username' => strtolower($post['Post']['User']['username'])),
                    array('class' => "user",'escape' => false));?>


    </div>
<?php endforeach;?>