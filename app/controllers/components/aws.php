<?php

App::import('Lib', 'AwsSkd', array('file' => 'Aws/sdk-1.4.1/sdk.class.php'));

class AwsComponent extends Object {

    /**
     * Returns the user's current sending limits.
     *
     * @return array
     *      Max24HourSend
     *           The maximum number of emails the user is allowed to send in a 24-hour interval.
     *    MaxSendRate
     *           The maximum number of emails the user is allowed to send per second.
     *    SentLast24Hours
     *           The number of emails sent during the previous 24 hours.
     */
    function ses_get_send_quota(){
        $ses = new AmazonSES();
        $quota = $ses->get_send_quota();
        $data = array();
        if($quota->status == 200){
            $data = $quota->body;
        }

        return $data;
    }

    function list_verified_email_addresses(){
        $ses = new AmazonSES();
        $quota = $ses->list_verified_email_addresses();
        $data = array();
        if($quota->status == 200){
            $data = $quota->body;
        }

        return $data;
    }

    /*
    delete_verified_email_address
           verify_email_address
get_send_statistics

    */


    function ec2_describe_images(){


        //debug($ses->list_verified_email_addresses());



        $ec2 = new AmazonEC2();



        $ec2->set_region(AmazonEC2::REGION_EU_W1);
        // Get the response from a call to the DescribeImages operation.
        $response = $ec2->describe_instances();

        $instances = array();

        foreach ($response->body->reservationSet->item as $item)
        {
            $tagSet = $item->instancesSet->item->tagSet;
            $name = '';
            if($tagSet instanceof CFSimpleXML){
                $name = $tagSet->item->value;
            }

            $instances[] =  array(
                            'id'    => (string) $item->instancesSet->item->instanceId,
                            'state' =>  (string) $item->instancesSet->item->instanceState->name,
                            'type'  => $instanceType = (string) $item->instancesSet->item->instanceType,
                            'time'  => $instanceTime = (string) $item->instancesSet->item->launchTime,
                            'loc'   => $instanceLoc = (string) $item->instancesSet->item->placement->availabilityZone,
                            'name'  => (string)$name);
        }
        return $instances;

    }



}



?>