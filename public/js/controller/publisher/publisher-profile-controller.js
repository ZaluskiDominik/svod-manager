'use strict'

app.controller("publisherProfileController", function ($scope, $http, $controller) {
    angular.extend(this, $controller('validateFormController', {$scope: $scope}));

    $scope.loadProfileData = function() {
        $http.get('/api/publisher')
            .then( (response) => {
                let data = response.data;
                document.querySelector('.profile-user-name').innerHTML = data.firstName;
                $scope.firstName = data.firstName;
                $scope.surname = data.surname;
                $scope.email = data.email;
                $scope.company = data.company;
                $scope.companyWebsite = data.companyWebsite;
                user.fromJsonObject(data);
                user.updateAccountBalance(data.accountBalance);
            })
    };

    $scope.submitPublisherData = function () {
        if (!$scope.validateForm($scope.publisherDataForm)) {
            return;
        }

        const form = document.querySelector('#publisherDataForm');
        $http.patch('/api/publisher', formFieldsToObjectConverter.convert(form))
            .then( () => {
                document.querySelector('.profile-user-name').innerHTML = $scope.firstName;
            })
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

    $scope.loadProfileData();
});
