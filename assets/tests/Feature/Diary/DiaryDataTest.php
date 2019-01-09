<?php

namespace Tests\Feature\Diary;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Robconvery\Laraveldiary\PackageServiceProvider;

class DiaryDataTest extends TestCase
{

    /**
     * @test
     * @group get_diary_test_data
     * @group diary
     */
    public function get_test_data()
    {
        // Arrange
        $this->withoutExceptionHandling();
        $start = Carbon::now();
        $end = Carbon::now()->addMonth();

        // Act
        $response = $this->get(route('diary-entries', [
            'start' => $start->toDateString(),
            'end' => $end->toDateString(),
        ]));

        // Assert
        $response->assertOk();
        $response->assertJsonStructure(['data']);
    }
}
