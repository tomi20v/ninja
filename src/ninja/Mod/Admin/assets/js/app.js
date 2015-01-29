"use strict"
define([
	'jquery'
], function(
	$
) {

	$.log = function() {
		console.log.apply(console, arguments);
	};

	return {
		domNode: null,
		init: function() {
			$.log('inited');
			this.domNode = $('ni-app');
			this.state.init();
		},
		subModuleProto: {
			inited: false,

			init: function() {
				if (!this.inited) {
					this._init();
					this.inited = true;
				}
			},
			// override this as needed
			_init: function() {},
			// I will be called when my UI (or one of its children) gets focus
			start: function(hashParts) {
				this.init();
				return this._start(hashParts);
			},
			// override this as needed
			_start: function(hashParts) {},
			// I will be called when my UI lose focus
			stop: function() {
				return this._stop();
			},
			// override this as needed
			_stop: function() {},
			// define submodules, as in example
			subModules: {
				//edit: $.extend({}, app.subModuleProto, {...})
			}
		},
		state: {
			activeModule: null,
			initedModules: [],
			init: function() {
				var othis = this;
				window.onhashchange = function() {
					othis.onHashChange(othis);
				}
				othis.onHashChange(othis);
			},
			// IE safe hash getter
			getHash: function() {
				return document.URL.substr(document.URL.indexOf('#')+1);
			},
			// when hash changes, I'll init and start a new module, while stopping current one
			onHashChange: function(othis) {

				if (othis.activeModule) {
					require([this.activeModule], function (module) {
						if (module.hasOwnProperty('stop')) {
							module.stop();
						}
					});
				};

				var hashParts = othis.getHash().split('/');
				var moduleName = hashParts.pop();

				if (othis.initedModules.indexOf(moduleName) === -1) {
					var config = {paths: {}};
					config.paths[moduleName] = "admin/js/" + moduleName;
					require.config(config);
					require([moduleName], function(module) {
						module.init();
					});
					othis.initedModules.push(moduleName);
					$.log('inited module: ' + moduleName);
				}

				require([moduleName], function(module) {
					module.start(hashParts);
				});
				othis.activeModule = moduleName;

				$.log('started module: ' + moduleName);

			}
		},
		util: {
			/**
			 * I init new tabs UI with an empty default tab and return this new tab
			 * @param jQuery $el parent container
			 * @param string title for the new tab
			 * @returns jQuery the new, empty tab
			 */
			prepareTabs: function($el, title) {
				var $newTab = $('<div title="' + title + '"></div>');
				$el.append($newTab);
				$el.tabs();
				return $newTab;
			}
		}
	}

})
