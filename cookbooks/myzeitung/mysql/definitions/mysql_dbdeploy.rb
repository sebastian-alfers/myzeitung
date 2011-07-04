define :mysql_dbdeploy do
  include_recipe "mysql::server"

  gem_package "mysql"
  mysql_database "create database" do
    host "localhost"
    username "root"
    password node[:mysql][:server_root_password]

    database params[:database]
    action :create_db
  end

  mysql_database "create changelog table for dbdeploy" do
    host "localhost"
    username "root"
    password node[:mysql][:server_root_password]
    database params[:database]

    # HINT: The query action does NOT select the database so we specify the
    #       full path for the table. But i think this is a bug and therefor
    #       this solution can break in the future. But then it's easy to fix
    #       for you with this hint :)
    sql <<-EOF
      CREATE TABLE IF NOT EXISTS `#{params[:database]}`.`changelog` (
        `change_number` int(11) NOT NULL,
        `complete_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `applied_by` varchar(100) NOT NULL,
        `description` varchar(500) NOT NULL,
        PRIMARY KEY (`change_number`)
      );
    EOF
    action :query
  end
end
