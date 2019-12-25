'use strict'

app.controller("publisherMyVideosController", function ($scope, $controller, $http) {
    angular.extend(this, $controller('myVideosController', {$scope: $scope}));

    $scope.fetchVideos('/api/publisher/videos');
});
