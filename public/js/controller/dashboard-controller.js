'use strict'

app.controller("dashboardController", function ($scope, $controller) {
    angular.extend(this, $controller('validateFormController', {$scope: $scope}));

    $scope.tabNr = 0;

    $scope.changeTab = function(tabNr) {
        if (tabNr === $scope.tabNr) {
            return;
        }

        $scope.$broadcast("dashboardTabChanged", {
            tabNr : tabNr
        });

        $scope.tabNr = tabNr;
    }
});
