<script src="{{ asset('js/controllers/diary-entry.component.js') }}"></script>
<script>
    var diaryApp = angular.module('diaryApp', [
        'diaryCtrl',
        'diaryDataCtrl',
        'diaryEntryCtrl',
        'diaryService'
    ]);
</script>
