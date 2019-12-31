'use strict'

app.controller("customerProfileController", function ($scope, $http, $controller) {
    angular.extend(this, $controller('validateFormController', {$scope: $scope}));

    $scope.loadProfileData = function() {
        $http.get('/api/customer')
            .then( (response) => {
                let data = response.data;
                document.querySelector('.profile-user-name').innerHTML = data.firstName;
                $scope.firstName = data.firstName;
                $scope.surname = data.surname;
                $scope.email = data.email;
                user.fromJsonObject(data);
                user.updateAccountBalance(data.accountBalance);
            })
    };

    $scope.submitCustomerData = function () {
        if (!$scope.validateForm($scope.customerDataForm)) {
            return;
        }

        const form = document.querySelector('#customerDataForm');
        $http.patch('/api/customer', formFieldsToObjectConverter.convert(form))
            .then( () => {
                document.querySelector('.profile-user-name').innerHTML = $scope.firstName;
            })
            .catch( (response) => {
                $scope.emailExistsErr = $scope.apiError = '';

                if (response.status === 409) {
                    $scope.emailExistsErr = response.data.message;
                } else {
                    $scope.apiError = "Unknown error happened";
                }
            });
    };

    $scope.loadProfileData();
});
