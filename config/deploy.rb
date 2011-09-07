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
set :copy_exclude, %w(.git)

set :keep_releases, 4
after "deploy:update", "deploy:cleanup"

role :web, "ec2-46-137-9-184.eu-west-1.compute.amazonaws.com"

set :deploy_to,   "/var/www/myzeitung/" #contains symlink "current", contians directory "releases" and "shared"
set :use_sudo, true



ssh_options[:forward_agent] = true
ssh_options[:user] = "mz"
ssh_options[:keys] = ["#{ENV['HOME']}/.ssh/mz.pem"] # make sure you also have the publickey



task :deploy_web do
  #run "ls -ls /"
  run "sudo su mz && cd /home/mz/src/myzeitung && git pull"
  run "cd /home/mz/deploy && su && ./deploy"
end
