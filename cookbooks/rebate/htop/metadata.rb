maintainer       "Rebate Networks"
maintainer_email "dev@rebatenetworks.com"
license          "Apache 2.0"
description      "Installs/Configures htop"
version          "0.1.0"

recipe "htop", "Installs htop package"

%w{ubuntu debian}.each do |os|
  supports os
end
