'use strict'

app.controller("myVideosController", function ($scope, $controller, $http) {
    angular.extend(this, $controller('validateFormController', {$scope: $scope}));
    $scope.filteredVideos = [];
    $scope.videos = [];
    $scope.VideosView = VideosView;
    $scope.myVideosView = VideosView.GRID;

    $scope.fetchVideos = function(fetchUrl) {
        $http.get(fetchUrl)
            .then( (response) => {
                $scope.filteredVideos = $scope.videos = response.data.videos;
                $scope.videosSearch = new AutocompleteSearch(
                    document.querySelector('#my-video-search'),
                    AutocompleteSearch.transformVideosToData($scope.videos)
                );
            });
    };

    $scope.filterVideos = function() {
        const pattern = $scope.videosSearch.getValue();

        $scope.filteredVideos = [];
        $scope.videos.forEach( (video) => {
            if (AutocompleteSearch.composeSearchKey(video).indexOf(pattern) !== -1) {
                $scope.filteredVideos.push(video);
            }
        });
    };
});
