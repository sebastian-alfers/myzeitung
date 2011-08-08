<?php if(is_array($response['data']['open_graph_data']) && !empty($response['data']['open_graph_data'])): ?>
    <li class="ui-state-default teaser-sort video">
    <span class="video-item">video</span>
    <a class="remove_li_item" name="img/post/2011/08/37693cfc748049e45d87b8c7d8b9aacd/4e401d2b59103_default.jpg" id="23" style="cursor: pointer; vertical-align: top;">entfernen</a>
    <img src="<?php echo $response['data']['open_graph_data']['image']; ?>" />
        <div class="item_data" style="display: none;">
        <input type="hidden" name="item_type" value="video" />
        <input type="hidden" name="img_name" value="<?php echo $response['data']['file_name']; ?>" />
        <?php foreach($response['data']['open_graph_data'] as $key => $value): ?>
            <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>" />
        <?php endforeach; ?>
        </div>
    </li>
<?php endif; ?>