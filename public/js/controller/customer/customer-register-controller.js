'use strict'

app.controller("customerRegisterController", function ($scope, $http, $controller) {
    angular.extend(this, $controller('validateFormController', {$scope: $scope}));

    $scope.submitRegister = function() {
        if (!$scope.validateForm($scope.customerDataForm)) {
            return;
        }

        const form = document.querySelector('#customerDataForm');
        $http.post('/api/customer', formFieldsToObjectConverter.convert(form))
            .then( (response) => { window.location = response.data.redirectUrl; })
            .catch( (response) => {
                $scope.emailExistsErr = $scope.apiError = '';

                if (response.status === 409) {
                    $scope.emailExistsErr = response.data.message;
                } else {
                    $scope.apiError = "Unknown error happened";
                }
            });
    };
});
