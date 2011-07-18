<?php
if(isset($message)){

    $response = json_decode($response, true);

    $element_id = 'message_'.$message['ConversationMessage']['id'];
    $response['data']['html'] = $this->element('conversations/message', array('message' =>$message, 'class' => 'hidden', 'id' => $element_id));
    $response['data']['id'] = $element_id;


    echo json_encode($response);
}

?>