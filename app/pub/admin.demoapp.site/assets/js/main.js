//define(["jquery", "jquery.alpha", "jquery.beta"], function($) {
//    //the jquery.alpha.js and jquery.beta.js plugins have been loaded.
//    $(function() {
//        $('body').alpha().beta();
//    });
//});

require.config({
    baseUrl: "assets/js",
    paths: {
        "jQuery": "jquery",
          "jqx-all": "jqwidgets/jqx-all",
//        "jqxcore": "jqwidgets/jqxcore",
//        "jqxbuttons": "jqwidgets/jqxbuttons",
//        "jqxpanel": "jqwidgets/jqxpanel",
//        "jqxscrollbar": "jqwidgets/jqxscrollbar",
//        "jqxtree": "jqwidgets/jqxtree"
    },
    shim: {
        "jqx-all": {
            export: "$",
            deps: ['jQuery']
        },
        "jqxcore": {
            export: "$",
            deps: ['jQuery']
        },
        "jqxbuttons": {
            export: "$",
            deps: ['jQuery', "jqxcore"]
        },
        "jqxpanel": {
            export: "$",
            deps: ['jQuery', "jqxcore"]
        },
        "jqxscrollbar": {
            export: "$",
            deps: ['jQuery', "jqxcore"]
        },
        "jqxtree": {
            export: "$",
            deps: ['jQuery', "jqxcore"]
        }
    }
});
require(["app"], function (App) {
    App.initialize();
});
