<?php
$response = json_decode($response, true);
$response['view'] = $this->element('categories/review', array('category' => $response['data']['Category']));
echo json_encode($response);
?>