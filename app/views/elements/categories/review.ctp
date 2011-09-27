<div>
    <h2><?php __('Are you sure that you want to delete this category?'); ?></h2>


    <?php

    //build plural / singular string
    $post_count = $category['post_count'];

    $post_posts = sprintf(__n('%s post', '%s posts', $post_count, true), $post_count);

    ?>

    <?php echo sprintf(__('Your category %1$s contains currently %2$s.', true), $category['name'], $post_posts); ?>


</div>
<div class="modal-buttons">
<form id="CategoryFormDelete" method="post" action="/categories/delete" accept-charset="utf-8">
            <div style="display:none;"><input type="hidden" name="_method" value="POST"></div>
            <input type="hidden" name="data[Category][id]" id="CategoryId" value="<?php echo $category['id']; ?>">
        </form>
    <ul>
        <li><a href="#" class="btn" onclick="$('#CategoryFormDelete').submit();"><span>+</span><?php __('Delete Category'); ?></a></li>
        <li><a href="#" class="btn" onclick="$('#dialog-category-edit').dialog('close');"><span>-</span><?php __('Cancel'); ?></a></li>
    </ul>
</div>