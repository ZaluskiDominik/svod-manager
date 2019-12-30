'use strict'

app.controller("publisherMyVideosController", function ($scope, $controller, $http) {
    angular.extend(this, $controller('myVideosController', {$scope: $scope}));
    let parentGoBackVideo = $scope.goBackVideo;

    $scope.goBackVideo = function() {
        if ($scope.myVideosView === VideosView.CREATE_NEW) {
            $scope.myVideosView = VideosView.GRID;
        } else {
            parentGoBackVideo();
        }
    };

    $scope.showCreateNewVideoPage = function() {
        $http.get('api/video/players')
            .then((response) => {
                videoPlayerSelect.setPlayers(response.data.players);
                $scope.myVideosView = VideosView.CREATE_NEW;
            });
    };

    $scope.onVideoPlayerSelected = function() {
        $scope.embedCode = $scope.getSelectedPlayer().templateEmbedCode;
        embedCodeInput.setValue($scope.embedCode);
    };

    $scope.getSelectedPlayer = function() {
        for (let i = 0 ; i < videoPlayerSelect.players.length ; i++) {
            if (videoPlayerSelect.players[i].name === $scope.selectedVideoPlayerName) {
                return videoPlayerSelect.players[i];
            }
        }

        return null;
    };

    $scope.submitNewVideo = function() {
        let valid = $scope.validateForm($scope.newVideoForm);

        $scope.videoDescriptionEmptyError = (videoDescriptionEditor.getTextValue().replace(/\s/g, '') === '');
        valid = valid && !$scope.videoDescriptionEmptyError;

        if (!valid) {
            return;
        }

        let data = formFieldsToObjectConverter.convert(document.querySelector("form[name='newVideoForm']"));
        data['description'] = videoDescriptionEditor.getHtmlValue();
        data['videoPlayer'] = $scope.selectedVideoPlayerName;
        data['embedCode'] = $scope.embedCode;
        $http.post('api/video', data)
            .then((response) => {
                $scope.video.push(response.data.video);
                $scope.myVideosView = VideosView.GRID;
            })
            .catch((response) => {
                if (response.status === 409) {
                    $scope.publisherAlreadyHasVideoTitle = response.data.message;
                }
            })
    };

    $scope.fetchVideos('/api/publisher/videos');
});
