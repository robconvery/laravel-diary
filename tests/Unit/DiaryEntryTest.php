<?php

namespace Robconvery\Laraveldiary\Tests\Unit;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
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
     * @group setting_datetime_value_converts_carbon
     */
    public function setting_datetime_value_converts_carbon()
    {
        // Arrange
        $FakeDiaryEntry = new FakeDiaryEntry();
        $this->assertNull($FakeDiaryEntry->datetime);

        // Act
        // set datetime to string
        $FakeDiaryEntry->datetime = Carbon::now()->toDateTimeString();

        // Assert
        $this->assertInstanceOf(Carbon::class, $FakeDiaryEntry->datetime);
    }

    /**
     * @test
     * @group make_fake_instance
     */
    public function make_fake_instance()
    {
        // Arrange
        $faker = Factory::create('en_GB');
        $day = Carbon::now();
        $id = 1;
        $title = $faker->company;
        $description = collect($faker->paragraphs)->implode("\n");
        $link = '/diary';
        $postcode = $faker->postcode;
        $location = $faker->city;
        $data = [
            'datetime' => $day,
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'link' => $link,
            'postcode' => $postcode,
            'location' => $location
        ];
        $this->assertGreaterThan(0, $id);

        // Act
        $FakeDiaryEntry = FakeDiaryEntry::fakeInstance($data);

        // Assert
        $this->assertInstanceOf(FakeDiaryEntry::class, $FakeDiaryEntry);
        $this->assertEquals($id, $FakeDiaryEntry->id);
        $this->assertEquals($FakeDiaryEntry->datetime->toDateString(), $day->toDateString());
        $this->assertEquals($title, $FakeDiaryEntry->title);
        $this->assertEquals($description, $FakeDiaryEntry->description);
        $this->assertEquals($link, $FakeDiaryEntry->link);
        $this->assertEquals($postcode, $FakeDiaryEntry->postcode);
        $this->assertEquals($location, $FakeDiaryEntry->location);
    }

    /**
     * @test
     * @group convert_diary_entry_to_array
     */
    public function convert_diary_entry_to_array()
    {
        // Arrange
        $faker = Factory::create('en_GB');
        $day = Carbon::now();
        $id = 1;
        $title = $faker->company;
        $description = collect($faker->paragraphs)->implode("\n");
        $link = '/diary';
        $postcode = $faker->postcode;
        $location = $faker->city;
        $FakeDiaryEntry = new FakeDiaryEntry([
            'datetime' => $day,
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'link' => $link,
            'postcode' => $postcode,
            'location' => $location
        ]);

        // Act
        $arr = $FakeDiaryEntry->toArray();

        // Assert
        $this->assertEquals($id, $arr['id']);
        $this->assertEquals($day->toDateString(), $arr['date']);
        $this->assertEquals($title, $arr['title']);
        $this->assertEquals($description, $arr['description']);
        $this->assertEquals($link, $arr['link']);
        $this->assertEquals($postcode, $arr['postcode']);
        $this->assertEquals($location, $arr['location']);
    }

    /**
     * @test
     * @group find_returns_diary_entry
     */
    public function find_returns_diary_entry()
    {
        // Arrange
        FakeDiaryEntry::makeFakeData();

        // Act
        $FakeDiaryEntry = FakeDiaryEntry::find(1);

        // Assert
        $this->assertInstanceOf(FakeDiaryEntry::class, $FakeDiaryEntry);
        $this->assertEquals(1, $FakeDiaryEntry->id);

    }

    /**
     * @test
     * @group make_fake_data
     */
    public function make_fake_data()
    {
        // Arrange
        $today = Carbon::now();
        // $period = cal_days_in_month(CAL_GREGORIAN, $today->month, $today->year);
        $period = 8;

        // Act
        FakeDiaryEntry::makeFakeData();

        // Assert
        $days = FakeDiaryEntry::fakeDays();
        $this->assertInstanceOf(Collection::class, $days);
        $this->assertCount($period, $days);

        foreach ($days as $day) {
            // each fake day should be an instance of Carbon
            $this->assertInstanceOf(Carbon::class, $day);

            // There should be available cache for each day. Cache value
            // should be an array
            $dayCache = Cache::get(FakeDiaryEntry::getDayCacheKey($day));
            $this->assertTrue(is_array($dayCache));
            $this->assertNotEmpty($dayCache);

            // each value of the array should be an
            // id relating to a fake diary entry.
            foreach ($dayCache as $id) {
                // there should be available cache for an fake
                // diary entry.
                $entryCache = Cache::get(FakeDiaryEntry::getEntryCacheKey($id));
                $this->assertTrue(is_array($entryCache));
                $this->assertNotEmpty($entryCache);
                $this->assertArrayHasKey('id', $entryCache);
                $this->assertArrayHasKey('date', $entryCache);
                $this->assertArrayHasKey('datetime', $entryCache);
                $this->assertArrayHasKey('link', $entryCache);
                $this->assertArrayHasKey('title', $entryCache);
                $this->assertArrayHasKey('description', $entryCache);
                $this->assertArrayHasKey('postcode', $entryCache);
                $this->assertArrayHasKey('location', $entryCache);
            }
        }
    }

    /**
     * @test
     * @group find_diary_entry
     */
    public function find_diary_entry()
    {
        // Arrange
        FakeDiaryEntry::makeFakeData();

        // Act
        $FakeDiaryEntry = FakeDiaryEntry::find(1);

        // Assert
        $this->assertInstanceOf(FakeDiaryEntry::class, $FakeDiaryEntry);
        $this->assertEquals(1, $FakeDiaryEntry->id);
        $this->assertInstanceOf(Carbon::class, $FakeDiaryEntry->datetime);
    }

    /**
     * @test
     * @group save_diary_entry
     */
    public function save_diary_entry()
    {
        // Arrange
        FakeDiaryEntry::makeFakeData();
        $diary = FakeDiaryEntry::find(1);
        $original = clone $diary;
        $new = Carbon::now()->addDay();
        $this->assertNotEquals($diary->datetime->toDateString(), $new->toDateString());

        // Act
        $diary->datetime = $new->toDateString();
        $diary->save();

        // Assert
        $entry = FakeDiaryEntry::find(1);
        $this->assertEquals($new->toDateString(), $entry->datetime->toDateString());

        $before = Cache::get(FakeDiaryEntry::getDayCacheKey($original->datetime));
        $after = Cache::get(FakeDiaryEntry::getDayCacheKey($new));

        $this->assertFalse(collect($before)->search($diary->id));
        $this->assertNotFalse(collect($after)->search($diary->id));
    }

    /**
     * @test
     * @group get_diary_entries
     */
    public function get_diary_entries()
    {
        // Arrange
        $FakeDiaryEntry = new FakeDiaryEntry();
        $date = Carbon::now();

        // Act
        $entries = $FakeDiaryEntry->entries($date);

        // Assert
        $this->assertInstanceOf(Collection::class, $entries);
        $this->assertTrue(is_array($entries->first()));
        // $this->assertCount(1, $entries);
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
