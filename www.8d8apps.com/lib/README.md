lib
===

Library of Very Reusable Code

sudo yum groupinstall -y "Web Server" "MySQL Database" "PHP Support"sudo yum groupinstall -y "Web Server" "MySQL Database" "PHP Support"
sudo service httpd start
sudo chkconfig httpd on
sudo yum install mysql mysql-server
sudo service mysqld start
sudo chkconfig mysqld on
sudo service httpd restart
updatedb
mysqladmin -u root password
iptables -A INPUT -p tcp --dport 80 -j ACCEPT
/sbin/iptables -A INPUT -m state --state NEW -p tcp --dport 80 -j ACCEPT
/sbin/iptables -A INPUT -m state --state NEW -p tcp --dport 443 -j ACCEPT

chmod 400 /root/.ssh/github_rsa
nano -wS ~/.ssh/config
  Host github.com
  IdentityFile ~/.ssh/github_rsa
git clone git@github.com:AdamCox9/8d8apps.git
git config --global user.name "AdamCox9"
git config --global user.email "adam.cox9@gmail.com"
git clone git@github.com:AdamCox9/8d8apps.git
git clone git@github.com:AdamCox9/8d8apps.git

tar -pczf name_of_your_archive.tar.gz /path/to/directory

mysqldump --opt -u [uname] -p[pass] [dbname] > [backupfile.sql]

