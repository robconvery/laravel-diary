
angular.module('forecast', []).
component('forecastSeventyTwoHour', {
    template: '<div ng-switch="$ctrl.loading">'+
        '<i ng-switch-when="true" class="fas fa-spinner fa-spin"></i>' +
        '<div ng-switch-when="false">'+
        '<div ng-repeat="forecast in $ctrl.forecasts">' +
        '<small>' +
        '<span class="mr-2">' +
        '<span ng-bind-html="$ctrl.weatherIcon(forecast.description)"></span>' +
        '</span>' +
        '<span ng-switch="(forecast.temp_min === forecast.temp_max)">' +
        '<span ng-switch-when="true">{{forecast.temp}}</span>' +
        '<span ng-switch-when="false">{{forecast.temp_min}}-{{forecast.temp_max}}</span>' +
        '\t&#176;</span>&nbsp;' +
        '<span>{{forecast.description}}</span>' +
        '</small>' +
        '</div>' +
        '</div>' +
        '</div>',
    controller: [
        'ForecastProvider',
        forecastController
    ],
    bindings: {
        entry: '='
    }
});

function forecastController(ForecastProvider) {

    var ctrl = this;

    ctrl.loading = true;
    ctrl.forecasts = [];
    ctrl.weatherIcon = function(text) {
        if (text.match(/snow/gi) !== null) {
            return '<i class="far fa-snowflake"></i>';
        } else if (text.match(/light rain/gi) !== null) {
            return '<i class="fas fa-cloud-sun-rain"></i>';
        } else if (text.match(/rain/gi) !== null) {
            return '<i class="fas fa-umbrella"></i>';
        } else if (text.match(/cloud/gi) !== null) {
            return '<i class="fas fa-cloud"></i>';
        } else if (text.match(/sun/gi) !== null) {
            return '<i class="fas fa-cloud-sun"></i>';
        } else if (text.match(/clear skies/gi) !== null) {
            return  '<i class="fas fa-sun"></i>';
        } else {
            return '<i class="fas fa-cloud-sun-rain"></i>';
        }
    };

    angular.element(document).ready(function(){

        let now = new Date();
        now.setDate(now.getDate()-1);
        let datetime = new Date(ctrl.entry.datetime);

        if (
            ctrl.entry.hasOwnProperty('postcode') &&
            ctrl.entry.postcode.length > 0 &&
            datetime > now
        ) {
            ForecastProvider.get(
                ctrl.entry.postcode,
                datetime.toISOString().slice(0,10)
            )
                .then(function (response) {
                    if (response.hasOwnProperty('data') && response.data.length) {
                        for (x in response.data) {
                            let entry = response.data[x];
                            ctrl.forecasts.push(entry);
                        }
                    }
                    ctrl.loading = false;
                }, function(data, status, headers, config) {
                    ctrl.loading = false;
                });
        } else {
            ctrl.loading = false;
        }
    });
}
