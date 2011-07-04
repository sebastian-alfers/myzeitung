php_pear_channel "components.ez.no" do
  action :discover
end
phpunit = php_pear_channel "pear.phpunit.de" do
  action :discover
end

php_pear "phpcpd" do
  channel phpunit.channel_name
  action :upgrade
end
