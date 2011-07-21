<?php
$response = json_decode($response, true);
$view = $this->element('topics/view', array('topics' => $response['data'], 'post_id' => $post_id));
$response['data'] = $view;
?>

<?php echo json_encode($response); ?>