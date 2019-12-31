let app = angular.module("app", []);

app.filter('trust', function($sce) {
    return function(html) {
        return $sce.trustAsHtml(html);
    };
});
