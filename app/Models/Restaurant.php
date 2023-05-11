<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $casts = [
        'opening_times_grouped' => 'array'
    ];
    protected $fillable = [
        'home_description',
        'menu_description'
    ];
    public function openingTimes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OpeningTime::class);
    }

    public function newsArticles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(News::class);
    }

    public function getOpeningTimesGroupedAttribute()
    {
        $openingTimes = $this->openingTimes()->get();

        $grouped = $openingTimes->groupBy(function ($openingTime) {
            return $openingTime->start_time . '-' . $openingTime->end_time;
        })->map(function ($group) {
            $weekdayAbbrevations = ['Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za', 'Zo'];
            $weekdays = $group->pluck('weekday_number')->sort()->values();
            $start = substr($group->first()->start_time,0,-3);
            $end = substr($group->first()->end_time,0,-3);

            $ranges = [];
            $currentRange = [$weekdayAbbrevations[$weekdays->first()]];

            foreach ($weekdays->slice(1) as $index => $weekday) {
                $day = $weekdayAbbrevations[$weekday];
                $prev = $weekdays[$index];

                if ($weekday - $prev == 1) {
                    $currentRange[] = $day;
                } else {
                    $ranges[] = $currentRange;
                    $currentRange = [$day];
                }
            }
            $ranges[] = $currentRange;
            $start_day = $ranges[0][0];
            $end_day = $ranges[count($ranges)-1][0];

            return $start_day . ' t/m ' . $end_day . ': ' . $start . '-' . $end . '<br>';
        })->values()->toArray();

        return $grouped;
    }
}
