<?php
/**
 * Class FakeDiaryEntry
 *
 * @package Robconvery\Laraveldiary
 * @author robconvery <robconvery@me.com>
 */

namespace Robconvery\Laraveldiary;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Faker\Factory;
use Illuminate\Support\Facades\Cache;

class FakeDiaryEntry implements DiaryEntryInterface
{
    /**
     * @var
     */
    private $id;

    /**
     * @var string $link
     */
    private $link;

    /**
     * @var string $title
     */
    private $title;

    /**
     * @var string $description
     */
    private $description;

    /**
     * @var Carbon $datetime
     */
    private $datetime;

    /**
     * @var string $location
     */
    private $location;

    /**
     * @var
     */
    private $postcode;

    /**
     * @var int
     */
    private $sequence=0;

    /**
     * FakeDiaryEntry constructor.
     * @param array|null $data
     */
    public function __construct(array $data=null)
    {
        if (is_array($data)) {
            $this->exchangeArray($data);
        }
    }

    /**
     * @param array $data
     */
    private function exchangeArray(array $data)
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->datetime = isset($data['datetime']) && $data['datetime'] instanceof Carbon ?
            $data['datetime'] : null;
        $this->location = isset($data['location']) && is_string($data['location']) ?
            $data['location'] : null;
        $this->postcode = isset($data['postcode']) && is_string($data['postcode']) ?
            $data['postcode'] : null;
        $this->link = isset($data['link']) && is_string($data['link']) ?
            $data['link'] : null;
        $this->title = isset($data['title']) && is_string($data['title']) ?
            $data['title'] : null;
        $this->description = isset($data['description']) && is_string($data['description']) ?
            $data['description'] : null;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        if (!is_string($name)) {
            throw new \RuntimeException('Invalid datatype.');
        }
        return isset($this->$name) ? $this->$name : null;
    }

    /**
     * @param $name
     * @param $value
     * @return void
     */
    public function __set($name, $value)
    {
        if (property_exists(static::class, $name)) {
            switch ($name) {
                case 'datetime':
                    $this->datetime = strtotime($value) ?
                        Carbon::parse($value) : null;
                    break;
                default:
                    $this->$name = $value;
            }
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'date' => $this->datetime->toDateString(),
            'datetime' => $this->datetime->toDateTimeString(),
            'link' => $this->link,
            'title' => $this->title,
            'description' => $this->description,
            'postcode' => $this->postcode,
            'location' => $this->location
        ];
    }

    /**
     * @param Carbon $date
     * @return Collection
     * @throws \Exception
     */
    public function entries(Carbon $date): Collection
    {
        if (!Cache::get(static::getDayCacheKey($date))) {
            static::makeFakeData();
        }
        return collect(static::getDay($date))
            ->map(function ($id) {
                return static::find($id)->toArray();
            });
    }

    /**
     * Currently only updates the datetime value
     * @throws \Exception
     */
    public function save()
    {
        // retrieve current values
        $current = static::find($this->id);
        if ($current->datetime != $this->datetime) {
            // remove from current day cache array
            if ($current->datetime instanceof Carbon) {
                $original = static::getDay($current->datetime);
                $position = collect($original)->search($this->id);
                if ($position !== false) {
                    static::storeDay($current->datetime, collect($original)
                        ->forget($position)
                        ->toArray()
                    );
                }
            }
            // add this `id` to the relevant day array
            if ($this->datetime instanceof Carbon) {
                $new = static::getDay($this->datetime);
                $position = collect($new)->search($this->id);
                if ($position === false) {
                    static::storeDay($this->datetime, collect($new)
                        ->push($this->id)
                        ->toArray()
                    );
                }
            }
            // update diary entry
            static::storeEntry($this->id, $this->toArray());
        }
        return true;
    }

    /**
     * @param int $id
     * @return FakeDiaryEntry
     */
    public static function find(int $id): FakeDiaryEntry
    {
        $data = Cache::get(static::getEntryCacheKey($id));
        $data = array_merge($data, [
            'date' => Carbon::parse($data['date']),
            'datetime' => Carbon::parse($data['datetime'])
        ]);
        return new static($data);
    }

    /**
     * @throws \Exception
     * @return void
     */
    public static function makeFakeData()
    {
        static::fakeDays()->map(function ($day) {
            // make the diary entries
            $ids = collect(
                range(0, random_int(0, 2)) // create 1, 2 or 3 entries
            )->map(function () use ($day) {
                return static::fakeInstance(
                    static::fakeData($day)
                );
            })->map(function ($entry) {
                // store diary entry data
                static::storeEntry($entry->id, $entry->toArray());
                return $entry->id;
            })->toArray();
            // store an array of entry ids for that day
            static::storeDay($day, $ids);
        });
    }

    /**
     * @param Carbon $day
     * @return array
     */
    private static function getDay(Carbon $day): array
    {
        $day = Cache::get(static::getDayCacheKey($day));
        return !is_array($day) ? [] : $day;
    }

    /**
     * @param Carbon $day
     * @param array $ids
     * @return void
     */
    private static function storeDay(Carbon $day, array $ids)
    {
        Cache::forever(static::getDayCacheKey($day), $ids);
    }

    /**
     * @param int $id
     * @param array $data
     */
    private static function storeEntry(int $id, Array $data)
    {
        Cache::forever(static::getEntryCacheKey($id), $data);
    }

    /**
     * @param array $data
     * @return FakeDiaryEntry
     * @throws \Exception
     */
    public static function fakeInstance(array $data): FakeDiaryEntry
    {
        return new static([
            'datetime' => isset($data['datetime']) ? $data['datetime'] : null,
            'id' => isset($data['id']) ? $data['id'] : null,
            'title' => isset($data['title']) ? $data['title'] : null,
            'description' => isset($data['description']) ? $data['description'] : null,
            'link' => isset($data['link']) ? $data['link'] : null,
            'postcode' => isset($data['postcode']) ? $data['postcode'] : null,
            'location' => isset($data['location']) ? $data['location'] : null
        ]);
    }

    /**
     * @param Carbon $day
     * @return array
     * @throws \Exception
     */
    private static function fakeData(Carbon $day)
    {
        $faker = Factory::create('en_GB');
        return [
            'datetime' => $day,
            'id' => static::getNextSequence(),
            'title' => $faker->company,
            'description' => collect($faker->paragraphs)->implode("\n"),
            'link' => random_int(0, 1) ?: '/diary',
            'postcode' => random_int(0, 1) ?: $faker->postcode,
            'location' => $faker->city
        ];
    }

    /**
     * @return Collection
     * @throws \Exception
     */
    public static function fakeDays(): Collection
    {
        $data = collect();
        foreach (static::getPeriod() as $day) {
            $data->push($day);
        }
        return $data;
    }

    /**
     * @param Carbon $day
     * @return string
     */
    public static function getDayCacheKey(Carbon $day)
    {
        return static::getCacheKey('diary-day' . $day->toDateString());
    }

    /**
     * @param int $id
     * @return string
     */
    public static function getEntryCacheKey(int $id)
    {
        return static::getCacheKey('diary-entry' . $id);
    }

    /**
     * @param $string
     * @return string
     */
    private static function getCacheKey($string)
    {
        return md5($string);
    }

    /**
     * @return \DatePeriod
     * @throws \Exception
     */
    private static function getPeriod(): \DatePeriod
    {
        return new \DatePeriod(
            Carbon::now()->subDays(1),
            new \DateInterval('P1D'),
            Carbon::now()->addWeek()
        );
    }

    /**
     * @return int
     */
    private static function getNextSequence()
    {
        $key = md5('next-sequence');
        $id = (int)Cache::get($key);
        $id++;
        Cache::forever($key, $id);
        return $id;
    }
}
