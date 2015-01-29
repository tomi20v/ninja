"use strict"
define([
	'jquery', 'app'
], function(
	$, app
) {

	return $.extend({}, app.subModuleProto, {
		_init: function() {
			$.log('_init: page');
			var $appPage = $('<div id="ni-app-page"></div>');
			$("#ni-app").append($appPage);
			var $newTab = app.util.prepareTabs($appPage, 'Site structure');

		},
		start: function(hashParts) {
			this.init();
		},
		subModules: {
			edit: $.extend({}, app.subModuleProto, {
				init: function(hashParts) {
					$.log('inited page edit');
				}
			})
		}
	});

});
