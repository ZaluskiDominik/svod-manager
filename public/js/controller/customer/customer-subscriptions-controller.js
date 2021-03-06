'use strict'

app.controller("customerSubscriptionsController", function ($scope, $rootScope, $http, $controller) {
    angular.extend(this, $controller('subscriptionsController', {$scope: $scope}));

    $scope.filterSubscriptions = function (filterEnum) {
        $scope.currentFilter = filterEnum;
        switch (filterEnum) {
            case 'my-subs':
                subscriptions.filterSubscriptions((elem, index) => {
                    return ($scope.subscriptions[index].activeTo !== null);
                });
                break;
            case 'all-subs':
                subscriptions.removeSubFilters();
                break;
            case 'not-purchased-subs':
                subscriptions.filterSubscriptions((elem, index) => {
                    return ($scope.subscriptions[index].activeTo === null);
                });
                break;
        }
    };

    $scope.openPurchaseDialog = function (subIndex) {
        $scope.Math = Math;
        $scope.subToPurchase = $scope.subscriptions[subIndex];
        if (user.accountBalance < $scope.subToPurchase.price) {
            return;
        }

        purchaseSubDialog.open();
    };

    $scope.purchaseSub = function () {
        $http.post('/api/subscription/purchase', {
            subscriptionId: $scope.subToPurchase.id,
            customerId: user.id
        })
            .then(() => {
                let date = new Date();
                date.setDate(date.getDate() + 30);
                const month = (date.getMonth() > 9) ? date.getMonth() : '0' + date.getMonth();
                const day = (date.getDate() > 9) ? date.getDate() : '0' + date.getDate();

                $scope.subToPurchase.activeTo = date.getFullYear() + '-' + month + '-' + day;
                $scope.filterSubscriptions($scope.currentFilter);

                user.updateAccountBalance(user.accountBalance - $scope.subToPurchase.price);

                $rootScope.$broadcast("subscriptionPurchased", {});

                Swal.fire(
                    'Success',
                    'Subscription has been purchased',
                    'success'
                );
            })
            .catch((response) => {
                Swal.fire(
                    'Error',
                    (response.status === 422) ? response.data.message : 'Something went wrong',
                    'error'
                );
            });
    };

    $scope.fetchSubscriptions({info: "purchased"});
});
