<?php

App::import('Lib', 'AwsSkd', array('file' => 'Aws/sdk-1.4.1/sdk.class.php'));

class AwsComponent extends Object {

    var $_amazon_ses = null;
    var $_amazon_ec2 = null;
    var $_amazon_cloudfront = null;

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
        $ses = $this->getSes();
        $quota = $ses->get_send_quota();
        $data = array();
        if($quota->status == 200){
            $data = $quota->body;
        }

        return $data;
    }

    function list_verified_email_addresses(){
        $ses = $this->getSes();
        $quota = $ses->list_verified_email_addresses();
        $data = array();
        if($quota->status == 200){
            $data = $quota->body;
        }

        return $data;
    }

    function ses_get_send_statistics(){
        $ses = $this->getSes();
        $statistics = $ses->get_send_statistics();
        $data = array();
        if($statistics->status == 200){
            $data = $statistics->body;
        }

        return $data;
    }



    function ec2_describe_images(){
        $ec2 = $this->getEc2();
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


    function cf_list_distributions(){
        $cloudfront = $this->getCloudfront();
        return $cloudfront->list_distributions();
    }

    function cf_list_invalidations($distribudion_id){
        $cloudfront = $this->getCloudfront();
        return $cloudfront->list_invalidations($distribudion_id);
    }

    function cf_create_invalidation($distribution_id, $paths){
        $cloudfront = $this->getCloudfront();
        return $cloudfront->create_invalidation($distribution_id, uniqid(), $paths);
    }

    function cf_get_invalidation($distribution_id, $invalidation_id){
        $cloudfront = $this->getCloudfront();
        return $cloudfront->get_invalidation($distribution_id, $invalidation_id);
    }


    function getCloudfront(){
        if($this->_amazon_cloudfront == NULL){
            $this->_amazon_cloudfront = new AmazonCloudFront();
        }
        return $this->_amazon_cloudfront;
    }

    function getSes(){
        if($this->_amazon_ses == NULL){
            $this->_amazon_ses = new AmazonSES();
        }
        return $this->_amazon_ses;
    }

    function getEc2(){
        if($this->_amazon_ec2 == NULL){
            $this->_amazon_ec2 = new AmazonEC2();
        }
        return $this->_amazon_ec2;
    }


}



?>