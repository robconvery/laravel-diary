<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class DiaryController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function diary()
    {
        return view('diary::diary');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function days()
    {
        return $this->daysView(Carbon::now());
    }

    /**
     * @param $date
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function daysWithDate($date)
    {
        return $this->daysView(Carbon::parse($date));
    }

    protected function daysView(Carbon $date)
    {
        // Calendar start
        $start = (clone $date)->startOfMonth()->startOfWeek();
        $end = (clone $date)->endOfMonth()->endOfWeek();

        if ($date->isToday() === true) {
            $start = (clone $date)->startOfWeek();
            $days = $date->diff($end)->days;
            if ($days < 14) {
                $end->addDays((14 - $days));
            }
        }

        return view('diary::month', [
            'date' => $date,
            'previous' => (clone $date)->subMonth(),
            'next' => (clone $date)->endOfMonth()->addDay(),
            'first' => $start,
            'last' => $end
        ]);
    }
}
