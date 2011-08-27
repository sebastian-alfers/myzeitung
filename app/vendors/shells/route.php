<?php
class RouteShell extends Shell{
    public $uses = array('Post', 'Paper');
    public function main(){

        $this->out("Refreshing Routes for Posts");
        $this->Post->refreshRoutes();
        $this->out("done...");
        $this->out("Refreshing Routes for Papers");
        $this->Paper->refreshRoutes();
        $this->out("done...");
    }
}
?>