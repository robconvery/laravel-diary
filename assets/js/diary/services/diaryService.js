
angular.module('diaryService', [])
    .factory('DiaryProvider', function($http) {
        return {

            month : function(date) {
                if (angular.isString(date) && date.length) {
                    return $http({
                        method: 'get',
                        url: '/diary/days/' + encodeURIComponent(date)
                    });
                } else {
                    return $http({
                        method: 'get',
                        url: '/diary/days'
                    });
                }
            },

            entries: function (date) {
                return $http({
                    method: 'get',
                    url: '/diary/entries/' + encodeURIComponent(date)
                });
            },

            update : function (id, params) {
                return $http({
                    method: 'post',
                    url: '/diary/' + encodeURIComponent(id) + '/update',
                    params: params
                });
            },

            reordered : function (day, ids) {
                return $http({
                    method: 'post',
                    url: '/diary/reordered/' + encodeURIComponent(day),
                    params: {
                        order: ids
                    }
                });
            }
        }
    });
