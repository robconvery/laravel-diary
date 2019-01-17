<div ng-drop="true"
     ng-drop-success="onDropComplete($data, '{{ $date->toDateString() }}', $event)"
     ng-init="init({date: '{{ $date->toDateString() }}', start: '{{ $start->toDateString() }}', end: '{{ $end->toDateString() }}'})"
     class="day day-calendar @if($date < \Carbon\Carbon::now()->startOfDay() && $today->isToday() === true)
 mobile-hide
@endif">

    <div class="text-right day-heading">

        @if(\Carbon\Carbon::now()->toDateString() == $date->toDateString())
        <span style="background-color: yellow;">
        @endif

        <div class="day-name">
            {{ $date->format('l') }}
        </div>

        <div class="day-date">

            <div class="d-inline">{{ $date->format('jS') }}</div>
            @if($date->day == 1 || $first === true)
            <div class="d-inline">
                <span class="short-month">{{ $date->format('M') }}</span>
            </div>
            @endif

        </div>

        @if(\Carbon\Carbon::now()->toDateString() == $date->toDateString())
        </span>
        @endif

    </div>

    <div class="day-data">

        <ul class="list-group" ng-repeat="entry in entries('{{$date->toDateString()}}')">

            <li ng-drag="true"
                ng-drag-data="{entry}"
                data-allow-transform="true"
                class="list-group-item @if(\Carbon\Carbon::now()->startOfDay() > $date)
                list-group-item-secondary
@else
                list-group-item-success
@endif">
                <div>
                    <a ng-if="entry.link.length > 0" href="@{{entry.link}}">@{{entry.title}}</a>
                    <span ng-if="entry.link == null">@{{entry.title}}</span>
                </div>

                <small>
                    @{{entry.location}}
                    <a ng-if="entry.postcode.length" target="_blank" href="https://maps.google.com/maps?q=@{{entry.postcode}}">
                        @{{entry.postcode}}
                    </a>
                </small>

            </li>
        </ul>
    </div>

</div>
