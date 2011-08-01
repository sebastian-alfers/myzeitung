# -- Some basic stuff we always want
require_recipe "apt"
require_recipe "vim"

# -- Apache
require_recipe "apache2"
require_recipe "apache2::mod_php5"
require_recipe "apache2::mod_rewrite"
require_recipe "apache2::mod_deflate"
require_recipe "apache2::mod_env"
require_recipe "apache2::mod_expires"

# -- add phpmyadmin to listening ports
template "#{node[:apache][:dir]}/ports.conf" do
  source "ports.conf.erb"
  group "root"
  owner "root"
  variables :apache_listen_ports => node[:myzeitung][:listen_ports]
  mode 0644
  notifies :restart, resources(:service => "apache2")
end

# -- install phpmyadmin
package "phpmyadmin" do
  case node[:platform]
  when "debian","ubuntu"
    package_name "phpmyadmin"
  end
  action :install
end


# -- PHP
require_recipe "php"
require_recipe "php::module_apc"
require_recipe "php::module_curl"
require_recipe "php::module_mysql"
require_recipe "php::module_fileinfo"
require_recipe "php::module_mcrypt"
require_recipe "php::module_gd"

# -- MySQL
require_recipe "mysql::server"

apache_site "000-default" do
    enable false
end

# -- vhost for myzeitung
web_app "myzeitung" do
    docroot node[:myzeitung][:docroot]
    server_name node[:myzeitung][:server_name]
    server_aliases node[:myzeitung][:server_aliases]
    server_port node[:myzeitung][:server_port]
end

# -- vhost for phpmyadmin
web_app "phpmyadmin" do
    docroot node[:phpmyadmin][:docroot]
    server_name node[:phpmyadmin][:server_name]
    server_aliases node[:phpmyadmin][:server_aliases]
    server_port node[:phpmyadmin][:server_port]
end

# - install jetty / solr
require_recipe "solr-jetty"

# -- copy schema.xml for solr
#execute "copy schema.xml" do
#  command "/"
#  cwd "#{node[:landingpagetool][:docroot]}/../"
#end


require_recipe "memcached"
require_recipe "php::module_memcache"