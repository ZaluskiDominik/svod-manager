'use strict'

app.controller("myVideosController", function ($scope, $controller, $http, $timeout) {
    angular.extend(this, $controller('validateFormController', {$scope: $scope}));
    $scope.filteredVideos = [];
    $scope.videos = [];
    $scope.VideosView = VideosView;
    $scope.myVideosView = VideosView.GRID;

    $scope.fetchVideos = function (fetchUrl) {
        $http.get(fetchUrl)
            .then((response) => {
                $scope.videos = response.data.videos;
                $scope.videosSearch = new AutocompleteSearch(
                    document.querySelector('#my-video-search'),
                    AutocompleteSearch.transformVideosToData($scope.videos),
                    () => {
                        $timeout($scope.filterVideos);
                    }
                );
                $scope.filterVideos();
            });
    };

    $scope.filterVideos = function () {
        const pattern = $scope.videosSearch.getValue();

        $scope.filteredVideos = [];
        $scope.videos.forEach((video) => {
            if (AutocompleteSearch.composeSearchKey(video).toUpperCase().indexOf(pattern.toUpperCase()) !== -1) {
                $scope.filteredVideos.push(video);
            }
        });
    };

    $scope.clearVideoFilters = function() {
        $scope.videosSearch.setValue('');
        $scope.filterVideos();
    };

    $scope.openVideoDescription = function (videoIndex) {
        $scope.selectedVideo = $scope.filteredVideos[videoIndex];
        $scope.myVideosView = VideosView.DESCRIPTION;
    };

    $scope.openVideoPlayer = function () {
        $http({
            url: '/api/videos',
            method: "GET",
            params: {videoId : $scope.selectedVideo.id}
        })
            .then((response) => {
                $scope.myVideosView = VideosView.PLAYER;
                $scope.selectedVideo.videoPlayer = response.data.video.videoPlayer;
                document.querySelector('.video-player-wrapper').innerHTML = $scope.selectedVideo.embedCode;
                videoPlayerBootstrapper.bootstrap($scope.selectedVideo.videoPlayer.name);
            });
    };

    $scope.goBackVideo = function () {
        switch ($scope.myVideosView) {
            case VideosView.DESCRIPTION:
                $scope.myVideosView = VideosView.GRID;
                break;
            case VideosView.PLAYER:
                $scope.myVideosView = VideosView.DESCRIPTION;
                videoPlayerBootstrapper.bootstrappedPlayer.stop();
                videoPlayerBootstrapper.bootstrappedPlayer = null;
                break;
        }
    };

    $scope.$on('dashboardTabChanged', (e, data) => {
        if (videoPlayerBootstrapper.bootstrappedPlayer) {
            $scope.goBackVideo();
        }
    });
});
