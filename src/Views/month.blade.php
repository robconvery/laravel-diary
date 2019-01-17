
<div>
    <a href="#" class="btn btn-link" ng-click="previous();">
        <i class="fas fa-chevron-left"></i>
        {{ $previous->format('M Y') }}
    </a>
    <a href="/diary" class="btn btn-link">
        Today
    </a>
    <a href="#" class="btn btn-link" ng-click="next();">
        {{ $next->format('M Y') }}
        <i class="fas fa-chevron-right"></i>
    </a>
</div>

<h1>{!! $date->format('<\s\t\r\o\n\g>F</\s\t\r\o\n\g> Y') !!}</h1>

<div class="row">
    @php
        $days = \Carbon\Carbon::now()->startOfWeek();
        $week = 7;
    @endphp
    @while($week > 0)
        @include('diary::name', ['date' => $days])
        @php
            $days->addDay();
            $week--;
        @endphp
    @endwhile
</div>

<input type="hidden" id="start" name="start" value="{{ $previous->toDateString() }}">
<input type="hidden" id="end" name="end" value="{{ $next->toDateString() }}">
<div class="row" ng-controller="diaryDataController">
@php
    $index = 0;
@endphp
@while ($current < $last)

    @include('diary::day', [
        'date' => $current,
        'first' => $index === 0 ?: false,
        'today' => $date,
        'start' => $first,
        'end' => $last
    ])
    @php
        $current->addDay();
        $index++;
    @endphp
@endwhile
</div>


