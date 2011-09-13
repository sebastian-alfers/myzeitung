<?php

App::import('Component', 'Installer');

class DbinstallShell extends Shell{

    public function main(){

        $installer = new InstallerComponent();

        $this->out($installer->index());



    }
}
?>