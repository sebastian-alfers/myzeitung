<?php

App::import('Lib', 'AwsSkd', array('file' => 'Aws/sdk-1.4.1/sdk.class.php'));

class AwsComponent extends Object {


    function describeInstances(){

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