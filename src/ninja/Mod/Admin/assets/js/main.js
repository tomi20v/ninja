require.config({
	baseUrl: '/assets',
	paths: {
		"jquery": 'jquery/dist/jquery',
		"app": 'admin/js/app'
	},
	shim: {
	}
});

require(['jquery', 'app'], function($, app) {
//	app.init();
});
