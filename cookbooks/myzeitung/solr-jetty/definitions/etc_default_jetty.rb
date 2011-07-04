#
# Cookbook Name:: solr-jetty
# Definition:: etc_default_jetty
#
# configure jetty
#

define :etc_default_jetty, :template => "etc_default_jetty.conf.erb" do
  
  
  template "/etc/default/jetty" do
    source params[:template]
    owner "root"
    group "root"
    mode 0644

    variables(
      :jetty_host => "0.0.0.0"
    )
    #if ::File.exists?("/etc/default/jetty")
    #  notifies :reload, resources(:service => "jetty"), :delayed
    #end
  end
  
end
