'use strict'

app.controller("publisherDashboardController", function ($scope, $http, $controller) {
    angular.extend(this, $controller('dashboardController', {$scope: $scope}));

    $scope.logout = function() {
        $http.post('/api/publisher/logout', {})
            .then( () => { window.location = '/publisher-login'; } )
    };
});
