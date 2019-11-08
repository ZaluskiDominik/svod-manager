'use strict'

app.controller("validateFormController", function ($scope) {
    $scope.validateForm = function(scopeForm) {
        if (scopeForm.$invalid) {
            angular.forEach(scopeForm.$error, function (field) {
                angular.forEach(field, function(errorField){
                    errorField.$setTouched();
                })
            });

            return false;
        }

        return true;
    }
});
