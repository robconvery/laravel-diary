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

    /**
     * @param DiaryEntryInterface $diary
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(DiaryEntryInterface $diary, Request $request, $id)
    {
        $datetime = $request->input('datetime');
        if (strtotime($datetime) === false) {
            abort(400);
        }

        $diary->datetime = Carbon::parse($datetime);
        $diary->save();
        return response()->json([
            'status' => 1
        ]);
    }
}
