phing = php_pear_channel "pear.phing.info" do
  action :discover
end

php_pear "phing" do
  channel phing.channel_name
  action :upgrade
end
