<?php
$response = json_decode($response, true);
$response['view'] = $this->element('topics/review', array('topic' => $response['data']['topic'], 'whole_user_in_paper_count' => $response['data']['whole_user_in_paper_count']));
echo json_encode($response);
?>