'use strict'

app.controller("subscriptionsController", function ($scope, $controller, $http) {
    angular.extend(this, $controller('validateFormController', {$scope: $scope}));

    $scope.subscriptions = [
        {
            name: "My subscription",
            publisher : {
                company: "Pro company"
            },
            videos : [
                {
                    title: "Super video"
                }
            ]
        },
        {
            name: "Other subscription"
        }
    ];

    $scope.openNewSubDialog = function() {
        $scope.resetSubDialogVideos();
        $scope.fetchSubDialogPublisherVideos();
        newSubscriptionDialog.open();
    };

    $scope.resetSubDialogVideos = function() {
        $scope.notSelectedSubDialogVideos = [];
        $scope.selectedSubDialogVideos = [];
    };

    $scope.fetchSubDialogPublisherVideos = function() {
        $http.get('/api/publisher/videos')
            .then( (response) => {
                $scope.notSelectedSubDialogVideos = response.data.videos;
                newSubscriptionDialog.refreshVideosSelect();
            });
    };

    $scope.addSubDialogVideo = function() {
        const selectedIndex = newSubscriptionDialog.getSelectedVideoIndex() - 1;
        if (selectedIndex === -1) {
            return;
        }

        const selectedVideo = $scope.notSelectedSubDialogVideos[selectedIndex];
        $scope.notSelectedSubDialogVideos.splice(selectedIndex, 1);
        $scope.selectedSubDialogVideos.push(selectedVideo);
        newSubscriptionDialog.refreshVideosSelect();
    };

    $scope.saveNewSub = function() {
        const form = newSubscriptionDialog.dialogNode.querySelector("form");
        if (!$scope.validateForm(form)) {
            return;
        }

        let data = formFieldsToObjectConverter.convert(form);
        data.videos = $scope.selectedSubDialogVideos;

        $http.post('/api/subscription', data)
            .then( (response) => {
                console.log(response.data);
            })
    };

    $scope.resetSubDialogVideos();
});
