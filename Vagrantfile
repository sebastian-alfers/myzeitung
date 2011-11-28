Vagrant::Config.run do |config|

  config.vm.box = "lucid64"

  config.vm.forward_port "myzeitung", 80, 8182
  config.vm.forward_port "solr", 8080, 8183
  config.vm.forward_port "phpmyadmin", 8182, 8185

  config.ssh.max_tries = 50

  config.vm.boot_mode = :gui
  config.vm.network "33.33.33.10"
  config.vm.share_folder("myzeitung", "/vagrant", "/Applications/MAMP/htdocs/myzeitung", :nfs => true)

Vagrant::Config.run do |config|
  config.vm.provision :chef_solo do |chef|
    chef.add_recipe("vagrant_main")
    #chef.add_recipe("myzeitung")

  end
end

end
