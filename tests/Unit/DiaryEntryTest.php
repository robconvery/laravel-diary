<?php

namespace Robconvery\Laraveldiary\Tests\Unit;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Support\Collection;
use Robconvery\Laraveldiary\DiaryEntryInterface;
use Robconvery\Laraveldiary\FakeDiaryEntry;
use Robconvery\Laraveldiary\PackageServiceProvider;
use Orchestra\Testbench\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DiaryEntryTest extends TestCase
{
    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [PackageServiceProvider::class];
    }

    /**
     * @test
     * @group get_diary_entries
     */
    public function get_diary_entries()
    {
        // Arrange
        $FakeDiaryEntry = $this->app->make(DiaryEntryInterface::class);
        $this->assertInstanceOf(FakeDiaryEntry::class, $FakeDiaryEntry);
        $date = Carbon::now();

        // Act
        $entries = $FakeDiaryEntry->entries($date);

        // Assert
        $this->assertInstanceOf(Collection::class, $entries);
        $this->assertInstanceOf(FakeDiaryEntry::class, $entries->first());
        $this->assertCount(1, $entries);
    }
}
