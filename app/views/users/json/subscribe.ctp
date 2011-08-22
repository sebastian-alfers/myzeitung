<?php
$response = json_decode($response, true);
if($response['status'] != 'reload'){
    //get view html
    $response['view'] = $this->element('users/subscribe', array('data' => $response['data']));
}
echo json_encode($response);
?>