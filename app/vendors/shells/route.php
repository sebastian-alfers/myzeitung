<?php
class RouteShell extends Shell{
    public $uses = array('Post', 'Paper');
    public function main(){
        $this->out("Alf ist doof.");
        $this->out();
        $this->out("Arbeit Arbeit. Das kann ich!");
        $this->out("Refreshing Post Routes");
        $this->Post->refreshRoutes();
        $this->out("done...");
        $this->out("Refreshing Paper Routes");
        $this->Paper->refreshRoutes();
        $this->out("done...");
    }
}
?>