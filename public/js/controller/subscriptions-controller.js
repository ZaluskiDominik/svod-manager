'use strict'

app.controller("subscriptionsController", function ($scope, $controller, $http) {
    angular.extend(this, $controller('validateFormController', {$scope: $scope}));
    $scope.subscriptions = [];
    $scope.currentFilter = 'all-subs';

    $scope.fetchSubscriptions = function(getParams = {}) {
        $scope.user = user;
        $http({
            url: '/api/subscriptions',
            method: "GET",
            params: getParams
        })
            .then((response) => {
                $scope.subscriptions = response.data;
            });
    };
});
