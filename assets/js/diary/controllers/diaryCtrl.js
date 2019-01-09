
angular.module('diaryCtrl', ['ngSanitize', 'ngDraggable'])
    .controller('diaryController', function ($scope, DiaryProvider, $compile){

        $scope.loading = true;

        $scope.next = function() {
            var date = document.getElementById("end").value;
            $scope.reload(date);
        };

        $scope.previous = function() {
            var date = document.getElementById("start").value;
            $scope.reload(date);
        };

        $scope.reload = function(date) {
            document.getElementById('content').innerHTML = '';
            $scope.loading = true;
            $scope.get(date);
        };

        $scope.get = function(date) {
            DiaryProvider.month(date).then(function (response) {
                var html = $compile(response.data)($scope);
                angular.element(document.getElementById("content")).append(html);
                $scope.loading = false;
            });
        };

        /**
         * load diary
         */
        $scope.get();

    });
