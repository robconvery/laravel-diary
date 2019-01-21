
angular.module('diaryEntryCtrl', []).
    component('diaryEntry', {
        template: '<div ng-switch="$ctrl.loading">'+
            '<i ng-switch-when="true" class="fas fa-spinner fa-spin"></i>' +
            '</div>',
        controller: [
            diaryEntryController
        ],
        bindings: {
            entry: '<'
        }
    });

function diaryEntryController() {

    var ctrl = this;

    ctrl.loading = true;
    angular.element(document).ready(function(){
        ctrl.loading = false;
    });
}
