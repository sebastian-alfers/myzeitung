<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'spaaace';
$this->data['Install']['version'] = 666;
$this->Install->save($this->data); 
*/

$sql[] = "CREATE TABLE `test_content_papers` (\n"
    . " `id` int(11) NOT NULL AUTO_INCREMENT,\n"
    . " `paper_id` int(11) NOT NULL,\n"
    . " `category_id` int(11) DEFAULT NULL,\n"
    . " `user_id` int(11) DEFAULT NULL,\n"
    . " `topic_id` int(11) DEFAULT NULL,\n"
    . " PRIMARY KEY (`id`)\n"
    . ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;";

//**** !IMPORTANT! ****
$log = 'needed to create manually since test did it not';




?>