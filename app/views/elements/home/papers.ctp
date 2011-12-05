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