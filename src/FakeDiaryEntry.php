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

class FakeDiaryEntry implements DiaryEntryInterface
{
    /**
     * @var Carbon $date
     */
    private $date;

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

    private $factory;

    /**
     * FakeDiaryEntry constructor.
     * @param array|null $data
     */
    public function __construct(array $data=null)
    {
        if (is_array($data)) {
            $this->exchangeArray($data);
            $this->factory = Factory::create();
        }
    }

    public function __get($name)
    {
        if (!is_string($name)) {
            throw new \RuntimeException('Invalid datatype.');
        }
        return isset($this->$name) ? $this->$name : null;
    }

    /**
     * @param array $data
     */
    private function exchangeArray(array $data)
    {
        $this->datetime = isset($data['datetime']) && $data['datetime'] instanceof Carbon ?
            $data['datetime'] : null;
        $this->link = isset($data['link']) && is_string($data['link']) ?
            $data['link'] : null;
        $this->title = isset($data['title']) && is_string($data['title']) ?
            $data['title'] : null;
        $this->description = isset($data['description']) && is_string($data['description']) ?
            $data['description'] : null;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'date' => $this->datetime->toDateString(),
            'link' => $this->link,
            'title' => $this->title,
            'description' => $this->description
        ];
    }

    /**
     * @param Carbon $date
     * @return Collection
     * @throws \Exception
     */
    public function entries(Carbon $date): Collection
    {
        $entries = $this->fakeData()->filter(function ($arr, $string) use($date) {
            $datetime = Carbon::parse($string);
            return $datetime->toDateString() == $date->toDateString();
        });
        return $entries;
    }

    /**
     * @return Collection
     * @throws \Exception
     */
    private function fakeData(): Collection
    {

        // a list of all the days in the current month
        $dates = $this->periodToCollection()->map(function ($date) {
            $data = [];
            for ($i=0; $i < random_int(1, 3); $i++) {
                array_push(
                    $data,
                    // Creates a fake diary entry
                    $this->createInstance(Factory::create(), $date)->toArray()
                );
            }
            return $data;
        });

        return $dates;
    }

    /**
     * @param \Faker\Generator $faker
     * @param Carbon $date
     * @return FakeDiaryEntry
     */
    private function createInstance(\Faker\Generator $faker, Carbon $date): FakeDiaryEntry
    {
        return new static([
            'datetime' => $date,
            'title' => $faker->company,
            'description' => collect($faker->paragraphs)->implode("\n"),
            'link' => '/diary'
        ]);
    }

    /**
     * @return Collection
     * @throws \Exception
     */
    private function periodToCollection(): Collection
    {
        $data = [];
        foreach ($this->getPeriod() as $date) {
            $data[$date->format('Y-m-d')] = $date;
        }
        return collect($data);
    }

    /**
     * returns all the date for the next month
     * @return \DatePeriod
     * @throws \Exception
     */
    private function getPeriod(): \DatePeriod
    {
        return new \DatePeriod(
            Carbon::now(),
            new \DateInterval('P1D'),
            Carbon::now()->addMonth()
        );
    }
}
