<?php
class SolrShell extends Shell{
    public $uses = array('Solr');

    public function main(){

        $this->out("Refreshing Entries for Posts");
        $this->Solr->refreshPostsIndex();
        $this->out("done...");
        $this->out("Refreshing Entries for Papers");
        $this->Solr->refreshPostsIndex();
        $this->out("done...");
        $this->out("Refreshing Entries for Users");
        $this->Solr->refreshUsersIndex();
        $this->out("done...");

    }
}
?>