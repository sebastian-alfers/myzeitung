phpmd = php_pear_channel "pear.phpmd.org" do
  action :discover
end

php_pear "PHP_PMD" do
  channel phpmd.channel_name
  action :upgrade
  preferred_state "alpha"
end
