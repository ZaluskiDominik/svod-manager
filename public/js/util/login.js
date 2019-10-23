'use strict'

let login = {
    validateForm : function($scope) {
        if ($scope.login.$invalid) {
            angular.forEach($scope.login.$error, function (field) {
                angular.forEach(field, function(errorField){
                    errorField.$setTouched();
                })
            });

            return false;
        }

        return true;
    },

    sendLoginRequest : function(requestUrl, $http, email, password) {
        return $http.post(requestUrl, {
            email : email,
            password : password
        });
    },

    redirectToDashboard : function() {
        window.location = "/dashboard";
    }
};
