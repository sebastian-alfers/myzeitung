<div>
    <h2><?php __('Are you sure?'); ?></h2>

    <?php
    //build plural / singular string
    $post_posts = sprintf(__n('%s post', '%s posts', $topic['Topic']['post_count'], true), $topic['Topic']['post_count']);
    $repost_reposts = sprintf(__n('%s repost', '%s reposts', $topic['Topic']['repost_count'], true), $topic['Topic']['repost_count']);
    ?>

    <?php echo sprintf(__('Your topic %s containts currently', true), $topic['Topic']['name']) . ' ' . $post_posts . ' ' . __('and', true). ' ' . $repost_reposts; ?>.

    <?php
    //check, if the the whole user ("no topic") is associated to a paper
    $additional_noes = '';
    if(isset($whole_user_in_paper_count) && $whole_user_in_paper_count > 0){
        $additional_noes = __('and will be published in' , true) . ' ' . sprintf(__n("%s paper", "%s papers", $whole_user_in_paper_count, true), $whole_user_in_paper_count);
    }
    ?>
    <?php echo __('The topic of these entry are going to be set to "no topic"', true) . ' ' .$additional_noes; ?>
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