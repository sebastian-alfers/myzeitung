<?php
/*
$response commes, by default, as an json_encoded array
*/
$response = json_decode($response, true);

$response['data']['video_item_html'] = $this->element('posts/add_edit/video_item', array('response' => $response));
echo json_encode($response);
?>