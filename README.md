Ninja is intended to be a simple CMS system implementing HMVC and based on Maui.
The core is a module system which can be stored into MongoDB through Maui's
	CRUD models, while using heavily related objects. Eg. a page to be generated
	is itself a module, which contains other modules, which contain others etc.
	Each module has its own model, view, controller representation, and a
	container which is the module's class itself.
In the meantime, Ninja has also evolved to support polymer.

v0.2.0 the systam has passed POC #2 - generate a skeleton page with polymer support
v0.1.0 the system has passed POC #1 - generate a page from loaded module hierarchy

INSTALL:

1. install composer (if needed)

2. install bower (if needed)

3. install all dependencies, run
./composer install

if you want the demo app site to be installed:

4. set up vhost
edit your httpd conf and set up so the webserver accepts demoapp.site domain
	and it points to ~ninja/pub/demoapp.site
4.1. make sure .htaccess can set options (AllowOverride FileInfo Limit)
4.2. install mod_rewrite it is needed for routing

5. set up hosts file so demoapp.site points to ninja installation

6. open http://demoapp.site/install/pages.php

7. open http://demoapp.site and you should see the home page

