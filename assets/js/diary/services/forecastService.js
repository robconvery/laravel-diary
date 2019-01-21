
angular.module('forecastService', [])
    .factory('ForecastProvider', function($http) {
        return {
            get: function (postcode, date) {
                return $http({
                    method: 'get',
                    url: '/forecast/get/' + encodeURIComponent(postcode) + '/' + encodeURIComponent(date)
                });
            }
        }
    });

