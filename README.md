## Wnemonic
Wnemonic is a self-hosted file manager with tags.

### Dependencies
- **php** 7
- **Laravel** 6
- **Composer**
- **Node** (wnemonic uses less preprocessor to create css)
- Your preferred db and web server (I uses nginx and postgresql)

### Deploy
	php composer install
	npm install
	npm run prod 
	php artisan key:generate
	php artisan migrate
	php artisan make:password # password is used to change site content (add, edit)

#### Then you need to manage folder permissions, e.g:
	chown -R USER:www-data /path/to/your/project/storage 
	
#### Configure your web server, nginx e.g:

```
server {

	listen  80;
	server_name wnemonic;
	root   /var/www/wnemonic/public;

	client_max_body_size 300M; # max upload file size

	add_header X-Frame-Options "SAMEORIGIN";
	add_header X-XSS-Protection "1; mode=block";
	add_header X-Content-Type-Options "nosniff";

	index index.php;

	location / {
	
		types {
			text/css css;
		}
		
		try_files $uri $uri/ /index.php;
	}

	location ~ \.php$ {
		include fastcgi_params;
		fastcgi_pass  unix:/run/php-fpm/php-fpm.sock;
		fastcgi_index index.php;
		fastcgi_param  SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
		include fastcgi_params;
	}

	location ~ /\.(?!well-known).* {
		deny all;
	}
}
```

#### Finally, deploy database and create .env file with your configurations
