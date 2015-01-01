require.config({
	baseUrl: '/assets',
	paths: {
		"jquery": 'jquery/jquery',
		"app": 'admin/js/app',
		"easyui": "easyui/jquery.easyui.min"
	},
	shim: {
		"easyui": { deps: ["jquery"] }
	}
});

require(['jquery', 'app'], function($, app) {
	app.init();
});
