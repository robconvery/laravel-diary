<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Robconvery\Laraveldiary\DiaryEntryInterface;

class DiaryDataController extends Controller
{
    /**
     * @param string $date
     * @return \Illuminate\Http\JsonResponse
     */
    public function entries($date)
    {
        return response()->json(
            app()->make(DiaryEntryInterface::class)
                ->entries(Carbon::parse($date))
                ->toArray()
        );
    }
}
