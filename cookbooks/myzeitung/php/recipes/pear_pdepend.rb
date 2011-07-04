require_recipe "imagemagick"

pdepend = php_pear_channel "pear.pdepend.org" do
  action :discover
end

php_pear "PHP_Depend" do
  channel pdepend.channel_name
  action :upgrade
  preferred_state "beta"
end
