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
        $this->assertTrue(is_array($entries->first()));
        $this->assertCount(1, $entries);
    }

    /**
     * @test
     * @group can_see_postcode
     */
    public function can_see_postcode()
    {
        // Arrange
        $faker = Factory::create('en_GB');
        $postcode = $faker->postcode;

        // Act
        $FakeDiaryEntry = $this->app->make(DiaryEntryInterface::class, [[
            'postcode' => $postcode
        ]]);

        // Assert
        $this->assertInstanceOf(FakeDiaryEntry::class, $FakeDiaryEntry);
        $this->assertEquals($postcode, $FakeDiaryEntry->postcode);
    }

    /**
     * @test
     * @group can_see_state
     */
    public function can_see_state()
    {
        // Arrange
        $faker = Factory::create('en_GB');
        $state = $faker->city;

        // Act
        $FakeDiaryEntry = $this->app->make(DiaryEntryInterface::class, [[
            'location' => $state
        ]]);

        // Assert
        $this->assertInstanceOf(FakeDiaryEntry::class, $FakeDiaryEntry);
        $this->assertEquals($state, $FakeDiaryEntry->location);
    }


}
