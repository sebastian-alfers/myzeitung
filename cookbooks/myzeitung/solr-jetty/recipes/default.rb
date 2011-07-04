# -- install java for solr
package "solr-jetty" do
  case node[:platform]
  when "debian","ubuntu"
    package_name "openjdk-6-jdk"
  end
  action :install
end


# -- install solr-jetty
package "solr-jetty" do
  case node[:platform]
  when "debian","ubuntu"
    package_name "solr-jetty"
  end
  action :install
end

# -- configure jetty
etc_default_jetty do
end

service "jety" do
  action :stop
end

service "jetty" do
  action :start
end
