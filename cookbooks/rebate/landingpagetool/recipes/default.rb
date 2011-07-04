# -- Some basic stuff we always want
require_recipe "apt"
require_recipe "vim"
require_recipe "htop"

# -- Apache
require_recipe "apache2"
require_recipe "apache2::mod_php5"
require_recipe "apache2::mod_rewrite"
require_recipe "apache2::mod_deflate"
require_recipe "apache2::mod_env"
require_recipe "apache2::mod_expires"

apache_site "000-default" do
    enable false
end

web_app "landingpagetool" do
    docroot node[:landingpagetool][:docroot]
    server_name node[:landingpagetool][:server_name]
    server_aliases node[:landingpagetool][:server_aliases]
end

# -- PHP
require_recipe "php"
require_recipe "php::module_apc"
require_recipe "php::module_curl"
require_recipe "php::module_mysql"
require_recipe "php::module_fileinfo"
require_recipe "php::module_mcrypt"

# -- Stuff for the development environment (to generate all the fancy reports)
if node[:landingpagetool][:environment] == "development"
  require_recipe "sloccount"
  require_recipe "php::pear_phing"
  require_recipe "php::pear_phpcpd"
  require_recipe "php::pear_phpcs"
  require_recipe "php::pear_pdepend"
  require_recipe "php::pear_phpdoc"
  require_recipe "php::pear_phpmd"
  require_recipe "php::pear_phpunit"
end

# -- MySQL
require_recipe "mysql::server"

mysql_dbdeploy do
    database "LandingPageTool"
end

# -- Execute DbDeploy to get the latest database layout
require_recipe "java"

execute "Run dbdeploy to install the database" do
  command "phing -Denvironment #{node[:landingpagetool][:environment]} dbdeploy"
  cwd "#{node[:landingpagetool][:docroot]}/../"
end
