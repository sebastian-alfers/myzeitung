set :application, "myzeitung"
set :repository,  "set your repository location here"

#default_run_options[:pty] = true  # Must be set for the password prompt from git to work
set :repository, "git@github.com:sebastian-alfers/myzeitung.git"
set :scm, "git"
set :user, "ubuntu"

set :branch, "master"
set :deploy_via, :remote_cache


role :web, "ec2-46-137-9-184.eu-west-1.compute.amazonaws.com"
role :app, "ec2-46-137-9-184.eu-west-1.compute.amazonaws.com"


ssh_options[:forward_agent] = true
ssh_options[:user] = "ubuntu"
ssh_options[:keys] = ["#{ENV['HOME']}/.ssh/mz.pem"] # make sure you also have the publickey



task :search_web do
  #run "cd /home/mz/src/myzeitung && sudo su mz && sudo git pull"
  run "cd /home/mz/deploy && sudo ./deploy"
end

task :count_web do
  run "ls -x1 /usr/lib | wc -l"
end