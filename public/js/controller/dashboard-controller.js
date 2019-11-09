'use strict'

app.controller("dashboardController", function ($scope, $controller) {
    angular.extend(this, $controller('validateFormController', {$scope: $scope}));

    $scope.tabNr = 0;

    $scope.changeTab = function(tabNr) {
        $scope.tabNr = tabNr;
    }
});