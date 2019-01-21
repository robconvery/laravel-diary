
angular.module('forecast', []).
component('forecastSeventyTwoHour', {
    template: '<div>' + '</div>',
    controller: [
        'ForecastProvider',
        forecastController
    ]
});

function forecastController(ForecastProvider) {

}
