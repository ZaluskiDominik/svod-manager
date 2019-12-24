'use strict'

app.controller("publisherRegisterController", function ($scope, $http, $controller) {
    angular.extend(this, $controller('validateFormController', {$scope: $scope}));

    $scope.submitRegister = function() {
        if (!$scope.validateForm($scope.publisherDataForm)) {
            return;
        }

        const form = document.querySelector('#publisherDataForm');
        $http.post('/api/publisher', formFieldsToObjectConverter.convert(form))
            .then( (response) => { window.location = response.data.redirectUrl; })
            .catch( (response) => {
                $scope.apiError = $scope.companyExistsErr = $scope.emailExistsErr = '';

                if (response.status === 409) {
                    if (response.data.errorField === 'company') {
                        $scope.companyExistsErr = response.data.message;
                    } else if (response.data.errorField === 'email') {
                        $scope.emailExistsErr = response.data.message;
                    }
                } else {
                    $scope.apiError = "Unknown error happened";
                }
            });
    };
});
