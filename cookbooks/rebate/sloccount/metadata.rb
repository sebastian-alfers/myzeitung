maintainer       "Rebate Networks"
maintainer_email "dev@rebatenetworks.com"
license          "Apache 2.0"
description      "Installs/Configures sloccount"
version          "0.1.0"

recipe "sloccount", "Installs sloccount package"

%w{ubuntu debian}.each do |os|
  supports os
end
