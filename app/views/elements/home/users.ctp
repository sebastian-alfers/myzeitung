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