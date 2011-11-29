set :application, "myzeitung"
set :repository,  "set your repository location here"

#default_run_options[:pty] = true  # Must be set for the password prompt from git to work
set :repository, "git@github.com:sebastian-alfers/myzeitung.git"
set :scm, "git"


# the user to connect to the server via ssh
set :user, "ubuntu"

set :deploy_via, :copy
set :copy_cache, "/Applications/MAMP/htdocs/myzeitung/deploy/"
set :copy_exclude, [".git/*", ".gitignore", "app/webroot/img*", "config/", "Capfile", "cookbooks/", ".gitmodules", "Vagrantfile", ".vagrant", ".git", "cake.tar.bz2"]

set :keep_releases, 4
after "deploy:update", "deploy:cleanup"


set :deploy_to,   "/var/www/myzeitung/" #contains symlink "current", contians directory "releases" and "shared"
set :use_sudo, true



ssh_options[:forward_agent] = true
ssh_options[:user] = "mz"
ssh_options[:keys] = ["#{ENV['HOME']}/.ssh/mz.pem"] # make sure you also have the publickey


# set live server
task :live do
    set :branch, "master"
    role :target, "ec2-46-137-170-80.eu-west-1.compute.amazonaws.com"
    role :target, "ec2-176-34-192-236.eu-west-1.compute.amazonaws.com" #rss
    #role :target, "ec2-46-137-170-80.eu-west-1.compute.amazonaws.com" , "ec2-46-137-65-146.eu-west-1.compute.amazonaws.com" #, "ec2-46-137-170-80.eu-west-1.compute.amazonaws.com", "ec2-46-137-59-207.eu-west-1.compute.amazonaws.com"
    #role :target, "ec2-46-137-170-80.eu-west-1.compute.amazonaws.com" #, "ec2-46-137-146-70.eu-west-1.compute.amazonaws.com" #, "ec2-46-137-170-80.eu-west-1.compute.amazonaws.com", "ec2-46-137-59-207.eu-west-1.compute.amazonaws.com"


    set :config, 'live'
end
# set staging server
task :staging do
    set :branch, "staging_4"
    role :target, "ec2-46-137-186-12.eu-west-1.compute.amazonaws.com"
    set :config, 'staging'
end

task :create_symlinks, :roles => :target do
    # link cake core files
    run "ln -s #{shared_path}/cake/ #{current_release}/cake"
    # link img folder to already mounted s3 (via s3fs to  /mnt/mzimg), done by /etc/ini.d/mount_media
    run "ln -s /mnt/mzimg #{current_release}/app/webroot/img"
    # rename config for live
    run "mv #{current_release}/app/config/core.php.#{config} #{current_release}/app/config/core.php"

    #set db config
    run "rm #{current_release}/app/config/database.php"
    run "mv #{current_release}/app/config/database.php.#{config} #{current_release}/app/config/database.php"

    # mount css/js cache
    run "ln -s /mnt/mzimg #{current_release}/app/webroot/csscache"
    run "ln -s /mnt/mzimg #{current_release}/app/webroot/jscache"

    # create tmp/cache folders
    run "sudo mkdir #{current_release}/app/tmp/"
    run "sudo mkdir #{current_release}/app/tmp/cache/"
    run "sudo chown www-data:www-data #{current_release}/app/tmp/cache/"
    run "sudo chmod 777 /var/www/myzeitung/current/app/tmp/cache/"

    run "sudo mkdir #{current_release}/app/tmp/cache/persistent/"
    run "sudo mkdir #{current_release}/app/tmp/cache/models/"
    run "sudo mkdir #{current_release}/app/tmp/cache/views/"

    ## set owner for cache
    run "sudo chown -R www-data:www-data #{current_release}/app/tmp/"

    run "sudo mkdir #{current_release}/app/tmp/cache/rss/"
    run "sudo chown -R www-data:www-data #{current_release}/app/tmp/cache/rss/"
    run "sudo chmod 777 #{current_release}/app/tmp/cache/rss/"

	if config == 'staging'
	    #copy htaccess
    	run "cp /home/ubuntu/.htpasswd #{current_release}/.htpasswd && cp /home/ubuntu/.htaccess #{current_release}/.htaccess"
	end

    # since cake console needs cache we create folders
    #run  tmp/cache/

end

task :upload_maintile, :via=> :scp, :recursive => true, :roles => :target do

      #upload("/Applications/MAMP/htdocs/myzeitung/app/webroot/img/assets/landing/bundestag-myzeitung.jpg", "/var/www/myzeitung/current/app/webroot/img/assets/landing/bundestag-myzeitung.jpg")

      #upload("/Applications/MAMP/htdocs/myzeitung/app/webroot/img/assets/default.jpg", "/var/www/myzeitung/current/app/webroot/img/assets/default.jpg")

      #upload("/Applications/MAMP/htdocs/myzeitung/app/webroot/img/assets/logo-icon.png", "/var/www/myzeitung/current/app/webroot/img/assets/logo-icon.png")
      #upload("/Applications/MAMP/htdocs/myzeitung/app/webroot/img/assets/loadinfo.gif", "#{current_release}/app/webroot/img/assets/loadinfo.gif")

      #upload("/Applications/MAMP/htdocs/myzeitung/app/webroot/img/assets/maintile.png", "#{current_release}/app/webroot/img/assets/maintile.png")
      #upload("/Applications/MAMP/htdocs/myzeitung/app/webroot/img/assets/ui/ui-icons_myz_256x240.png", "#{current_release}/app/webroot/img/assets/ui/ui-icons_myz_256x240.png")
      
      #upload("/Applications/MAMP/htdocs/myzeitung/app/webroot/img/assets/admin/arrows-ffffff.png", "#{current_release}/app/webroot/img/assets/ui/arrows-ffffff.png")
      #upload("/Applications/MAMP/htdocs/myzeitung/app/webroot/img/assets/admin/shadow.png", "#{current_release}/app/webroot/img/assets/ui/shadow.png")

      #upload("/Applications/MAMP/htdocs/myzeitung/app/webroot/img/assets/admin/shadow.png", "#{current_release}/app/webroot/img/assets/ui/shadow.png")
      
      #upload("/Applications/MAMP/htdocs/myzeitung/app/webroot/img/assets/pres_prev.jpg", "#{current_release}/app/webroot/img/assets/pres_prev.jpg")


#      upload("/Applications/MAMP/htdocs/myzeitung/app/webroot/img/assets/mzslides/home/p1.png", "#{current_release}/app/webroot/img/assets/mzslides/home/p1.png")                  
#      upload("/Applications/MAMP/htdocs/myzeitung/app/webroot/img/assets/mzslides/home/p2.png", "#{current_release}/app/webroot/img/assets/mzslides/home/p2.png")                  
#      upload("/Applications/MAMP/htdocs/myzeitung/app/webroot/img/assets/mzslides/home/p3.png", "#{current_release}/app/webroot/img/assets/mzslides/home/p3.png")                  
#      upload("/Applications/MAMP/htdocs/myzeitung/app/webroot/img/assets/mzslides/home/p4.png", "#{current_release}/app/webroot/img/assets/mzslides/home/p4.png")                  
#      upload("/Applications/MAMP/htdocs/myzeitung/app/webroot/img/assets/mzslides/home/p5.png", "#{current_release}/app/webroot/img/assets/mzslides/home/p5.png")                  
#      upload("/Applications/MAMP/htdocs/myzeitung/app/webroot/img/assets/mzslides/home/p6.png", "#{current_release}/app/webroot/img/assets/mzslides/home/p6.png")                  
#      upload("/Applications/MAMP/htdocs/myzeitung/app/webroot/img/assets/mzslides/home/p7.png", "#{current_release}/app/webroot/img/assets/mzslides/home/p7.png")                  
#      upload("/Applications/MAMP/htdocs/myzeitung/app/webroot/img/assets/mzslides/home/p8.png", "#{current_release}/app/webroot/img/assets/mzslides/home/p8.png")                  
#      upload("/Applications/MAMP/htdocs/myzeitung/app/webroot/img/assets/mzslides/home/p9.png", "#{current_release}/app/webroot/img/assets/mzslides/home/p9.png")                  

end

#task :upload_maintile, :via=> :scp, :recursive => true, :roles => :target do
#      upload("/Applications/MAMP/htdocs/myzeitung/app/webroot/img/assets/maintile.png", "#{current_release}/app/webroot/img/assets/maintile.png")
#end

task :dbinstall, :via=> :scp, :recursive => true, :roles => :target do
    run "sudo /var/www/myzeitung/current/cake/console/cake -app /var/www/myzeitung/current/app/ dbinstall"
end

task :deletecache, :via=> :scp, :recursive => true, :roles => :target do

    # remove js / css cache
    run "sudo rm /mnt/mzimg/*.js"
    run "sudo rm /mnt/mzimg/*.css"

    # create folder for js and css cache
    #run "mkdir #{current_release}/app/webroot/csscache/"
    #run "mkdir #{current_release}/app/webroot/jscache/"

    # permission for js and css cache
    #run "sudo chown www-data:www-data #{current_release}/app/webroot/csscache/"
    #run "sudo chown www-data:www-data #{current_release}/app/webroot/jscache/"
end

task :sync_time, :via=> :scp, :recursive => true, :roles => :target do
   run "sudo ntpdate ntp.ubuntulinux.org"
end

# After deployment has successfully completed
# create the .htaccess symlink
after "deploy:finalize_update", :create_symlinks

after "deploy:finalize_update", :upload_maintile

#need to run this after symlink have been created
#after "deploy:finalize_update", :dbinstall