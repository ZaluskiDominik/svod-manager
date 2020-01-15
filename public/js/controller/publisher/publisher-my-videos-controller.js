'use strict'

app.controller("publisherMyVideosController", function ($scope, $controller, $http) {
    angular.extend(this, $controller('myVideosController', {$scope: $scope}));
    let parentGoBackVideo = $scope.goBackVideo;

    $scope.goBackVideo = function () {
        if ($scope.myVideosView === VideosView.CREATE_NEW) {
            $scope.myVideosView = VideosView.GRID;
        } else if ($scope.myVideosView === VideosView.EDIT) {
            $scope.myVideosView = VideosView.DESCRIPTION;
        } else {
            parentGoBackVideo();
        }
    };

    $scope.showCreateNewVideoPage = function () {
        $scope.videoCreateEditTitle = 'Create new video';
        $http.get('api/video/players')
            .then((response) => {
                $scope.newVideoForm.$setUntouched();
                $scope.posterUrl = '';
                document.querySelector('label[for="poster-url"]').classList.remove('active');
                $scope.title = '';
                document.querySelector('label[for="title"]').classList.remove('active');
                videoDescriptionEditor.setHtmlValue('');
                videoPlayerSelect.setPlayers(response.data.players);
                $scope.embedCode = '';
                embedCodeInput.setValue('');

                $scope.myVideosView = VideosView.CREATE_NEW;
                $scope.selectedVideo = null;
            })
    };

    $scope.onVideoPlayerSelected = function () {
        $scope.embedCode = $scope.getSelectedPlayer().templateEmbedCode;
        embedCodeInput.setValue($scope.embedCode);
    };

    $scope.getSelectedPlayer = function () {
        for (let i = 0; i < videoPlayerSelect.players.length; i++) {
            if (videoPlayerSelect.players[i].name === $scope.selectedVideoPlayerName) {
                return videoPlayerSelect.players[i];
            }
        }

        return null;
    };

    $scope.submitNewVideo = function () {
        $scope.publisherAlreadyHasVideoTitle = $scope.newVideoApiError = '';

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
        data['id'] = ($scope.selectedVideo !== null) ? $scope.selectedVideo.id : null;

        $http({
            method : ($scope.myVideosView === VideosView.CREATE_NEW) ? "POST" : "PUT",
            url : "/api/videos",
            data : data
        }).then((response) => {
                $scope.myVideosView = VideosView.GRID;
                if ($scope.selectedVideo) {
                    $scope.videos[$scope.findVideoIndexById($scope.videos, data['id'])] = response.data.video;
                    $scope.filteredVideos[$scope.findVideoIndexById($scope.filteredVideos, data['id'])] =
                        response.data.video;
                } else {
                    $scope.videos.push(response.data.video);
                    $scope.filteredVideos.push(response.data.video);
                    $scope.videosSearch.setData(AutocompleteSearch.transformVideosToData($scope.videos));

                    Swal.fire(
                        'Success',
                        'Video has been created',
                        'success'
                    );
                }
            })
            .catch((response) => {
                if (response.status === 409) {
                    $scope.publisherAlreadyHasVideoTitle = response.data.message;
                } else {
                    $scope.newVideoApiError = 'Unknown error happened';
                }
            })
    };

    $scope.showEditVideoPage = function () {
        $scope.videoCreateEditTitle = 'Edit video';
        $http.get('api/video/players')
            .then((response) => {
                videoPlayerSelect.setPlayers(response.data.players);
                $scope.myVideosView = VideosView.EDIT;

                videoPlayerSelect.selectPlayer($scope.selectedVideo.videoPlayer.name);
                $scope.selectedVideoPlayerName = $scope.selectedVideo.videoPlayer.name;
                videoPlayerSelect.init();

                $scope.embedCode = $scope.selectedVideo.embedCode;
                embedCodeInput.setValue($scope.embedCode);

                $scope.title = $scope.selectedVideo.title;
                document.querySelector('label[for="title"]').classList.add('active');

                $scope.posterUrl = $scope.selectedVideo.posterUrl;
                document.querySelector('label[for="poster-url"]').classList.add('active');

                videoDescriptionEditor.setHtmlValue($scope.selectedVideo.description);
            });
    };

    $scope.findVideoIndexById = function(videos, id) {
        for (let i = 0 ; i < videos.length ; i++) {
            if (id === videos[i].id) {
                return i;
            }
        }

        return -1;
    };

    $scope.showDeleteVideoDialog = function() {
        deleteVideoDialog.open();
    };

    $scope.deleteVideo = function() {
        $http({
            url : '/api/videos',
            method : 'DELETE',
            data: {
                id : $scope.selectedVideo.id
            },
            headers : {
                'Content-Type' : 'application/json'
            }
        }).then(() => {
            $scope.videos.splice($scope.findVideoIndexById($scope.videos, $scope.selectedVideo.id), 1);
            $scope.filteredVideos.splice($scope.findVideoIndexById($scope.filteredVideos, $scope.selectedVideo.id), 1);
            $scope.myVideosView = VideosView.GRID;

            Swal.fire(
                'Success',
                'Video has been deleted',
                'success'
            );
        }).catch(() => {
            Swal.fire(
                'Error',
                'Something went wrong',
                'error'
            );
        });
    };

    $scope.fetchVideos('/api/publisher/videos');
});
