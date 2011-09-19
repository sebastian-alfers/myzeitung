set :application, "myzeitung"
set :repository,  "set your repository location here"

#default_run_options[:pty] = true  # Must be set for the password prompt from git to work
set :repository, "git@github.com:sebastian-alfers/myzeitung.git"
set :scm, "git"
set :branch, "master"

# the user to connect to the server via ssh
set :user, "ubuntu"

set :deploy_via, :copy
set :copy_cache, true
set :copy_exclude, [".git/*", ".gitignore", "app/webroot/img*", "config/", "Capfile", "cookbooks/", ".gitmodules", "Vagrantfile", ".vagrant", ".git", "cake.tar.bz2"]

set :keep_releases, 4
after "deploy:update", "deploy:cleanup"

role :web, "ec2-46-137-170-80.eu-west-1.compute.amazonaws.com"#, "ec2-46-51-139-74.eu-west-1.compute.amazonaws.com"

set :deploy_to,   "/var/www/myzeitung/" #contains symlink "current", contians directory "releases" and "shared"
set :use_sudo, true



ssh_options[:forward_agent] = true
ssh_options[:user] = "mz"
ssh_options[:keys] = ["#{ENV['HOME']}/.ssh/mz.pem"] # make sure you also have the publickey


task :create_symlinks, :roles => :web do
    # link cake core files
    run "ln -s #{shared_path}/cake/ #{current_release}/cake"
    # link img folder to already mounted s3 (via s3fs to  /mnt/mzimg), done by /etc/ini.d/mount_media
    run "ln -s /mnt/mzimg #{current_release}/app/webroot/img"
    # rename config for live
    run "mv #{current_release}/app/config/core.php.live #{current_release}/app/config/core.php"

    # mount css/js cache
    run "ln -s /mnt/mzimg #{current_release}/app/webroot/csscache"
    run "ln -s /mnt/mzimg #{current_release}/app/webroot/jscache"

    # create folder for js and css cache
    #run "mkdir #{current_release}/app/webroot/csscache/"
    #run "mkdir #{current_release}/app/webroot/jscache/"
    # permission for js and css cache
    #run "sudo chown www-data:www-data #{current_release}/app/webroot/csscache/"
    #run "sudo chown www-data:www-data #{current_release}/app/webroot/jscache/"

    # create tmp/cache folders
    run "mkdir #{current_release}/app/tmp/"
    run "mkdir #{current_release}/app/tmp/cache/"
    run "mkdir #{current_release}/app/tmp/cache/persistent/"
    run "mkdir #{current_release}/app/tmp/cache/models/"
    # set owner for cache
    run "sudo chown -R www-data:www-data #{current_release}/app/tmp/"

    #copy htaccess
    run "cp /home/ubuntu/.htpasswd /var/www/myzeitung/current && cp /home/ubuntu/.htaccess /var/www/myzeitung/current"

    # since cake console needs cache we create folders
    #run  tmp/cache/

end

task :upload_maintile, :via=> :scp, :recursive => true, :roles => :web do
      upload("/Applications/MAMP/htdocs/myzeitung/app/webroot/img/assets/maintile.png", "#{current_release}/app/webroot/img/assets/maintile.png")
end

task :dbinstall, :via=> :scp, :recursive => true, :roles => :web do
    stamp = Time.now.utc.strftime("%Y%m%d%H%M.%S")
    # run db upgrade, do NOT use the #{current_release} since this has no mount to cake core
    run "sudo /var/www/myzeitung/current/cake/console/cake -app /var/www/myzeitung/current/app/ dbinstall"
end


# After deployment has successfully completed
# create the .htaccess symlink
after "deploy:finalize_update", :create_symlinks

after "deploy:finalize_update", :upload_maintile

#need to run this after symlink have been created
#after "deploy:finalize_update", :dbinstall