'use strict'

app.controller("customerMyVideosController", function ($scope, $controller, $http) {
    angular.extend(this, $controller('myVideosController', {$scope: $scope}));

    $scope.fetchVideos('/api/customer/videos');
});
