<?php
$response = json_decode($response, true);
$response['view'] = $this->element('topics/review', array('topic' => $response['data']['topic']));
echo json_encode($response);
?>