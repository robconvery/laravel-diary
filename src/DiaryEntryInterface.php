<?php
/**
 * Interface DiaryEntryInterface
 * @package Robconvery\Laraveldiary
 */

namespace Robconvery\Laraveldiary;

use Carbon\Carbon;
use Illuminate\Support\Collection;

interface DiaryEntryInterface
{
    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @param Carbon $date
     * @return Collection
     */
    public function entries(Carbon $date): Collection;
}
