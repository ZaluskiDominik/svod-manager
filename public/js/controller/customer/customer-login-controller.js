'use strict'

angular.module("app", []).controller("customerLoginController", function($scope, $http) {
    $scope.submitLogin = function() {
        if (!login.validateForm($scope))
            return;

        login.sendLoginRequest("/api/customer/login", $http, $scope.email, $scope.password)
            .then(login.redirectToDashboard.bind(login), () => {
                $scope.apiError = "Customer not found";
            });
    }
});
