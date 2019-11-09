'use strict'

app.controller("customerDashboardController", function ($scope, $http, $controller) {
    angular.extend(this, $controller('dashboardController', {$scope: $scope}));

    $scope.logout = function() {
          $http.post('/api/customer/logout', {})
              .then( () => { window.location = '/customer-login'; } )
    };
});
