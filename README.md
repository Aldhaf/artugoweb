## INSTALL COMPOSER
curl -sS https://getcomposer.org/installer -o ~/composer-setup.php
HASH=`curl -sS https://composer.github.io/installer.sig`
php -r "if (hash_file('SHA384', '/mnt/data/ROVI/projects/artugo/artugo-website/composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
sudo php ~/composer-setup.php --install-dir=/usr/local/bin --filename=composer

## Cek error log webserver httpd
tail -f /var/log/httpd/error_log


## Cek error log laravel
tail -f /var/www/private/storage/logs/namalognya



<!-- sudo ln -s /mnt/data/ROVI/projects/artugo/artugo-website/ /var/www/artugo-website -->

# COBA sampai step ini dlu
sudo chmod -Rf 0777 /mnt/data/ROVI/projects/artugo/artugo-website-newui/private/storage/logs
sudo chmod -Rf 0777 /var/www/private/storage/framework/views
sudo chmod -Rf 0777 /var/www/private/storage/framework/sessions
sudo chmod -Rf 0777 /var/www/private/storage/framework/laravel-excel
sudo chmod -Rf 0777 /var/www/private/storage/framework/cache

sudo chmod -Rf 0777 /var/www/html/uploads
sudo chmod -Rf 0777 /var/www/html/sys_uploads
sudo chmod -Rf 0777 /var/www/html/assets/uploads
## kalau masih problem coba dibawah


find /var/www/private/ -type d -exec chmod 755 {} ;
find /var/www/private/ -type d -exec chmod ug+s {} ;
find /var/www/private/ -type f -exec chmod 644 {} ;
chown -R {username}:www-data /var/www/private
chmod -R 777 /var/www/private/storage
chmod -R 777 /var/www/private/bootstrap/cache/

sudo chmod -Rf ug+rwx /var/www/private/storage /var/www/private/bootstrap/cache



### Get file access control lists:
getfacl /var/www/html

##### https://serverfault.com/questions/819332/centos-7-selinux-user-cant-write-in-the-webroot-of-apache
# Restablish the SELInux context:
sudo restorecon -Rv /var/www/html
# Change the owner of the webroot:
sudo chown -R devweb:devweb /var/www/html
# Change basic permissiones:
sudo chmod -R g+w /var/www/html
sudo chmod g+s /var/www/html
# Establish SELinux permissions:
sudo chcon -Rt httpd_sys_content_t /var/www/html
sudo chcon -Rt httpd_sys_rw_content_t /var/www/html/uploads
sudo chcon -Rt httpd_sys_rw_content_t /var/www/html/assets/uploads
sudo chcon -Rt httpd_sys_rw_content_t /var/www/html/index.php
# Establish ACL permissions:
sudo setfacl -R -m u:devweb:rwx /var/www/html
sudo setfacl -R -m d:u:devweb:rwx /var/www/html
sudo setfacl -R -m g:devweb:rwx /var/www/html
sudo setfacl -R -m d:g:devweb:rwx /var/www/html


composer install

cp ./.env.example ./.env
php artisan key:generate
php artisan config:cache && php artisan config:clear && php artisan route:cache && php artisan route:clear
php artisan vendor:publish




mysql -h 127.0.0.1 -u root -proot artugo < /mnt/data/ROVI/projects/artugo/artugo_dbs20.sql





eval "$(ssh-agent -s)" && ssh-add /mnt/data/ROVI/projects/artugo/id_github




### Pindahkan folder dari web lama
mv /var/www/html/assets/uploads /var/www/html/assets/uploads-old
mv /var/www/html/uploads /var/www/html/uploads-old
mv /var/www-old/html/assets/uploads /var/www/html/assets/uploads
mv /var/www-old/html/uploads /var/www/html/uploads
mv /var/www-old/html/sys_uploads /var/www/html/sys_uploads