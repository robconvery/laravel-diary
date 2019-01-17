
angular.module('diaryDataCtrl', ['ngDraggable'])
    .controller('diaryDataController', function ($scope, DiaryProvider){

        $scope.day = null;
        $scope.diary = [];

        $scope.entries = function(date) {
            return $scope.diary[date];
        };

        $scope.getDate = function() {
            return $scope.day.date;
        };

        $scope.getEntries = function(date) {
            DiaryProvider.entries(date).then(function(response){
                $scope.diary[date] = response.data;
            });
        };

        $scope.onDragComplete = function(data, evt) {
            console.log("drag success, data:", data);
        };

        $scope.onDropComplete = function(data, to, evt) {

            let date = data.entry.date;
            let id = data.entry.id;
            let from = $scope.entries(date).indexOf(data.entry);

            if (from > -1) {
                $scope.entries(date).splice(from, 1);
                DiaryProvider.update(id, {
                    'datetime': to
                }).then(function (response) {
                    /*console.log(to);*/
                    $scope.getEntries(to);
                }, function(data, status, headers, config) {
                    /*alert('error ' + data);*/
                    console.log(data.status, data);
                    /*alert(data);*/
                });

            }
        };

        $scope.init = function(obj) {
            // console.log('init called', obj);
            $scope.day = obj;
            $scope.getEntries($scope.getDate());
        };
    });
