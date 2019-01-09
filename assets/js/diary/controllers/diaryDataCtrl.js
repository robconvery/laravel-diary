
angular.module('diaryDataCtrl', [])
    .controller('diaryDataController', function ($scope, DiaryProvider){

        $scope.day = null;
        $scope.entries = [];

        $scope.getDate = function() {
            return $scope.day.date;
        };

        $scope.getEntries = function() {
            DiaryProvider.entries($scope.getDate()).then(function(response){
                $scope.entries = response.data[$scope.getDate()];
            });
        };

        $scope.init = function(obj){
            $scope.day = obj;
            $scope.getEntries();
        };
    });
