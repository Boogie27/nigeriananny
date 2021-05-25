RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^([^\.]+)$ $1.php [NC,L]

# php -- BEGIN cPanel-generated handler, do not edit
# Set the “ea-php73” package as the default “PHP” programming language.
<IfModule mime_module>
  AddHandler application/x-httpd-ea-php73 .php .php7 .phtml
</IfModule>
# php -- END cPanel-generated handler, do not edit
RewriteCond %{HTTPS} off
RewriteCond %{HTTP:X-Forwarded-SSL} !on
RewriteCond %{HTTP_HOST} ^nigerianannycompany\.com$ [OR]
RewriteCond %{HTTP_HOST} ^www\.nigerianannycompany\.com$
RewriteRule ^(.*)$ "https\:\/\/nigerianannycompany\.com\/$1" [R=301,L]

