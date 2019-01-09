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
        $date = Carbon::now();

        // Act
        $response = $this->get(route('diary-entries', [
            'date' => $date->toDateString()
        ]));

        // Assert
        $response->assertOk();
        $response->assertJsonStructure([$date->toDateString()]);
    }
}
