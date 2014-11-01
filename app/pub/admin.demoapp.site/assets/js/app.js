//// Configure loading modules from the lib directory,
//// except 'app' ones,
//requirejs.config({
//    "baseUrl": "assets/lib",
//    "paths": {
//        "app": "../app"
//    },
//    "shim": {
//        "jquery.alpha": ["jquery"],
//        "jquery.beta": ["jquery"]
//    }
//});
//
//// Load the main app module to start the app
//requirejs(["app/main"]);

//define(["jQuery", "jqxcore", "jqxbuttons", "jqxtree", "jqxpanel", "jqxscrollbar"], function () {
//define(["jQuery", "jqxtree", "jqxscrollbar"], function () {
define(["jQuery", "jqx-all"], function () {
    var initialize = function () {
        $(document).ready(function () {
            $('#jqxTree').jqxTree({ height: '300px', width: '300px' });
            $('#jqxTree').css("visibility", "visible");
        });
    };
    return {
        initialize: initialize
    };
});
