'use strict'

app.controller("publisherSubscriptionsController", function ($scope, $http, $controller) {
    angular.extend(this, $controller('subscriptionsController', {$scope: $scope}));

    $scope.filterSubscriptions = function(filterEnum) {
        switch (filterEnum)
        {
            case 'my-subs':
                subscriptions.filterSubscriptions((elem, index) => {
                     return ($scope.subscriptions[index].publisher.id === user.id);
                });
                break;
            case 'all-subs':
                subscriptions.removeSubFilters();
                break;
        }
    };

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
        if (!$scope.validateForm($scope.newSubscriptionForm)) {
            return;
        }

        if (!$scope.selectedSubDialogVideos.length) {
            $scope.nonEmptySubVideosError = 'Subscription should have at least one video';
            return;
        }

        let data = formFieldsToObjectConverter.convert(newSubscriptionDialog.dialogNode.querySelector("form"));
        data.videos = $scope.selectedSubDialogVideos;

        $http.post('/api/subscription', data)
            .then( (response) => {
                newSubscriptionDialog.close();
                $scope.subscriptions.push(response.data);
            })
            .catch( (response) => {
                if (response.status === 409) {
                    $scope.newSubErrorAPI = response.data.message;
                } else {
                    $scope.newSubErrorAPI = 'Unknown error happened';
                }
            })

    };

    $scope.deleteSub = function(subIndex) {
        var req = {
            method : 'DELETE',
            url : '/api/subscription',
            data : {
                id : $scope.subscriptions[subIndex].id
            }
        };

        $http(req)
            .then( (response) => {
                if (response.status === 202) {
                    $scope.subscriptions.splice(subIndex, 1);
                    subscriptions.removeSubscriptionNode(subIndex);
                }
            })
    };

    $scope.resetSubDialogVideos();
    $scope.fetchSubscriptions();
});