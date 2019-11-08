'use strict'

app.controller("publisherLoginController", function ($scope, $http) {
    $scope.apiError = "";

    $scope.submitLogin = function() {
        if (!login.validateForm($scope))
            return;

        login.sendLoginRequest("/api/publisher/login", $http, $scope.email, $scope.password)
            .then(login.redirectToDashboard.bind(login), () => {
                $scope.apiError = "Publisher not found";
            });
    }
});
