<?php if(isset($items)): ?>
    <ul>
        <?php foreach ($items as $item): ?>
                <li><?php echo ($item->get_title()) . ' ' . $item->get_permalink(); ?></li> <hr />
        <?php endforeach; ?>
    </ul>

<?php endif; ?>