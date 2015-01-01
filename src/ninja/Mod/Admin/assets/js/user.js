"use strict"
define([
	'jquery', 'app'
], function(
	$, app
) {

	return $.extend({}, app.subModuleProto, {
		_init: function() {

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