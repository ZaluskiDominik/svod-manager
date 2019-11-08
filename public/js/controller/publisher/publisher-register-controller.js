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
                if (response.status === 409) {
                    $scope.apiError = response.data.message;
                } else {
                    $scope.apiError = "Unknown error happened";
                }
            });
    };
});
