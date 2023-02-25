# In real CI, image FROM should be changed to one built in ./Dockerfile
FROM example_app_php_fpm

RUN composer install --no-autoloader # --no-autoloader to make this step cacheable
RUN composer dump-autoload --optimize