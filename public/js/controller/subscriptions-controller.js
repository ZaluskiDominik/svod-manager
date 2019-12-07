'use strict'

app.controller("subscriptionsController", function ($scope, $controller, $http) {
    angular.extend(this, $controller('validateFormController', {$scope: $scope}));
    $scope.subscriptions = [];

    $scope.fetchSubscriptions = function() {
        $scope.user = user;
        $http.get('/api/subscriptions')
            .then( (response) => {
                $scope.subscriptions = response.data;
            })
    };

    $scope.filterSubscriptions = function(filterEnum) {
        switch (filterEnum)
        {
            case 'my-subs':
                Array.from(document.querySelectorAll('.subscriptions > ul > li')).forEach( (elem, index) => {
                    elem.style.display = ($scope.subscriptions[index].publisher.id === user.id) ? 'block' : 'none';
                });
                break;
            case 'all-subs':
                $scope.removeSubFilters();
                break
        }
    };

    $scope.removeSubFilters = function() {
        Array.from(document.querySelectorAll('.subscriptions > ul > li')).forEach( (elem) => {
            elem.style.display = 'block';
        })
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
