
angular.module('diaryDataCtrl', ['ngDraggable'])
    .controller('diaryDataController', function ($scope, DiaryProvider){

        $scope.day = null;
        $scope.diary = [];

        $scope.createEntry = function(obj)
        {
            return {
                id: obj.id,
                datetime: obj.datetime,
                link: obj.hasOwnProperty('link') ? obj.link : null,
                title: obj.hasOwnProperty('title') ? obj.title : null,
                description: obj.hasOwnProperty('description') ? obj.description : null,
                postcode: obj.hasOwnProperty('postcode') ? obj.postcode : null,
                location: obj.hasOwnProperty('location') ? obj.location : null
            };
        };

        $scope.getDate = function() {
            return $scope.day.date;
        };

        $scope.getEntries = function(date) {
            DiaryProvider.entries(date).then(function(response){

                if ($scope.diary.hasOwnProperty(date) === false) {
                    $scope.diary[date] = [];
                }

                if (response.data.length > 0) {
                    for (let x in response.data) {
                        let obj = response.data[x];
                        obj.datetime = new Date(obj.datetime);
                        $scope.diary[date].push($scope.createEntry(obj));
                    }
                }
            });
        };

        $scope.onDragComplete = function(index, data, evt) {
            if (data === null) {
                return false;
            }
            let dateString = data.entry.datetime.toISOString().slice(0,10);
            let initial = $scope.displayOrder($scope.diary[dateString]);
            for (let i=0; i < $scope.diary[dateString].length; i++) {
                if ($scope.diary[dateString][i].id === data.entry.id) {
                    // remove from array
                    $scope.diary[dateString].splice(i, 1);
                }
            }
            // add back to array in correct position
            $scope.diary[dateString].splice(index, 0, $scope.createEntry(data.entry));
            let after = $scope.displayOrder($scope.diary[dateString]);
            if (initial != after) {
                console.log(initial, $scope.displayOrder($scope.diary[dateString]));
                DiaryProvider.reordered(dateString, after.join(','))
            }

        };

        $scope.displayOrder = function(arr) {
            let data = [];
            for (let i=0; i < arr.length; i++) {
                data.push(arr[i].id);
            }return data;
        };

        $scope.onDropComplete = function(data, to, evt) {

            try {
                if (data === null) {
                    return false;
                }

                if (data.entry.hasOwnProperty('id') === false) {
                    throw 'invalid entry';
                }
                // convert to YYYY-MM-DD
                let dateString = data.entry.datetime.toISOString().slice(0,10);
                let toArr = to.split('-', 3);
                let id = data.entry.id;
                let index = $scope.inDay(data.entry.id, $scope.diary[dateString]);

                if (index !== false) {
                    // remove from day
                    $scope.diary[dateString].splice(index, 1);
                }

                // add to new day
                data.entry.datetime.setFullYear(toArr[0]);
                data.entry.datetime.setMonth((toArr[1] - 1));
                data.entry.datetime.setDate(toArr[2]);
                $scope.diary[to].push($scope.createEntry(data.entry));

                DiaryProvider.update(id, {
                    'datetime': to
                }).then(function (response) {
                    // nothing at the moment
                }, function(data, status, headers, config) {
                    console.log(data.status, data);
                });

            } catch (e) {
                alert(e.message ? e.message : e);
                console.log($scope.diary);
            }
        };

        $scope.init = function(obj) {
            $scope.day = obj;
            $scope.getEntries($scope.getDate());
        };

        $scope.inDay = function (key, list) {

            try {
                if (list.length === 0) {
                    throw 'array is empty';
                }
                for (let i=0; i < list.length; i++) {
                    if (list[i].id == key) {
                        return i;
                    }
                }
                return false;
            } catch(e) {
                console.log('error', e.message ? e.message : e);
                return false;
            }
        }
    });
