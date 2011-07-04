default[:myzeitung][:server_port] = "80"
default[:myzeitung][:environment] = "local"
default[:myzeitung][:server_name] = "myzeitung"
default[:myzeitung][:server_aliases] = ["myzeitung"]
default[:myzeitung][:docroot] = "/vagrant/app/webroot"

normal['mysql']['bind_address'] = "localhost"

default[:myzeitung][:solr_host] = "localhost"
default[:myzeitung][:solr_port] = "8080"
default[:myzeitung][:solr_path] = "solr"

default[:myzeitung][:listen_ports] = [ "80","443", "8182" ]


default[:phpmyadmin][:server_port] = "8182"
default[:phpmyadmin][:environment] = "local"
default[:phpmyadmin][:server_name] = "phpmyadmin"
default[:phpmyadmin][:server_aliases] = ["phpmyadmin"]
default[:phpmyadmin][:docroot] = "/usr/share/phpmyadmin"

normal['mysql']['bind_address'] = "localhost"