demo app resides here

To install:
Ninja is in a very early stage and has no sophisticated installer.

1. clone git repo. Ninja is not yet packeg in a nice composer package sorry
2. run composer install in Ninja folder to install dependencies
3. set up your local dns lookup (edit /etc/hosts on *nix systems) so demoapp.site points to localhost (or server)
4. set up your webserver so 'http://demoapp.sitedemosite.app' points to path/to/ninja/app/pub/demoapp.site
4.1. you might want to enable REWRITE_ALL for this folder if it is not enabled globally
5. in browser, open http://demoapp.sitedemoapp.site/install/pages.php - this will install basic page structure
6. open http://demoapp.site, it should display the demo application index page

and this is all what it does for now...


