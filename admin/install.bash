parent_path=$( cd "$(dirname "${BASH_SOURCE[0]}")" ; pwd -P )

read -p "Enter host: " host
read -p "Enter database name: " dbname
read -p "Enter UTORid: " utorid
read -s -p "Enter password: " password

echo -e "\n"
read -s -p "Enter htaccess password: " htaccesspass

echo -e "\n"

cd "$parent_path"
sed -i "s/hosthere/$host/g" ../api/api.php
sed -i "s/dbnamehere/$dbname/g" ../api/api.php
sed -i "s/userhere/$utorid/g" ../api/api.php
sed -i "s/passwordhere/$password/g" ../api/api.php

export PGPASSWORD=$password
psql -h mcsdb.utm.utoronto.ca -d $dbname -U $utorid -f schema.sql
htpasswd -bms ../htpasswd self $htaccesspass
path=`pwd`/../htpasswd 
sed -i "s@pathhere@$path@g" ../.htaccess
