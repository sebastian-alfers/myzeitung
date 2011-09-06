<div>
    <h2><?php __('Are you sure?'); ?></h2>

    <?php
    //build plural / singular string
    $post_count = $topic['Topic']['post_count'] - $topic['Topic']['repost_count'];
    $repost_count = $topic['Topic']['repost_count'];

    $post_posts = sprintf(__n('%s post', '%s posts', $post_count, true), $post_count);
    $repost_reposts = sprintf(__n('%s repost', '%s reposts', $repost_count, true), $repost_count);
    ?>

    <?php echo sprintf(__('Your topic %1$s contains currently %2$s and %3$s.', true), $topic['Topic']['name'], $post_posts, $repost_reposts); ?>

    <?php
    //check, if the the whole user ("no topic") is associated to a paper
    $additional_notes = '';
    if(isset($whole_user_in_paper_count) && $whole_user_in_paper_count > 0){
        $papers =
        $additional_notes = sprintf(__('These resetted entries will be published in %s.' , true), sprintf(__n("%s paper", "%s papers", $whole_user_in_paper_count, true), $whole_user_in_paper_count));
    }
    ?>
    <?php echo __('The topic of these entries will resetted to "no topic".', true); ?>
    <?php if(!empty($additional_notes)):?>
        <?php echo $additional_notes;?>
    <?php endif;?>
</div>
<div class="modal-buttons">
<form id="TopicForm" method="post" action="/topics/delete" accept-charset="utf-8">
            <div style="display:none;"><input type="hidden" name="_method" value="POST"></div>
            <input type="hidden" name="data[Topic][id]" id="TopicId" value="<?php echo $topic['Topic']['id']; ?>">
        </form>
    <ul>
        <li><a href="#" class="btn" onclick="$('#TopicForm').submit();"><span>+</span><?php __('Delete Topic'); ?></a></li>
        <li><a href="#" class="btn" onclick="$('#dialog-topic').dialog('close');"><span>-</span><?php __('Cancel'); ?></a></li>
    </ul>
</div>