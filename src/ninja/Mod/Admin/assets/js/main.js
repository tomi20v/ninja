require.config({
	paths: {
		jquery: '/assets/jquery/jquery.js',
		app: '/assets/admin/js/app.js'
	}
});

require(['jquery', 'app'], function($, app) {
	app.init();
});
