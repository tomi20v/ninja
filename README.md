Ninja is intended to be a simple CMS system implementing HMVC and based on Maui.
The core is a module system which can be stored into MongoDB through Maui's CRUD models, while using heavily
	related objects. Eg. a page to be generated is itself a module, which contains other modules, which contain
	others etc. Each module has its own model, view, controller representation, and a container which is the
	module's class itself.

note: the system has passed first POC - generate a page from loaded module hierarchy

this project focuses on PHP. to keep everything in packagist and reduce dependencies, bower components are installed
through the composer-asset-plugin
