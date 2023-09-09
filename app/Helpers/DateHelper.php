<?php

namespace App\Helpers;

use Carbon\Carbon;
use Carbon\CarbonInterface;

class DateHelper
{
    public static function currentWeekDays(): array
    {
        $weekDays = [];
        $currentDate = Carbon::now();
        $startOfWeek = $currentDate->copy()->previous(CarbonInterface::MONDAY);

        while ($startOfWeek->lte($currentDate)) {
            $weekDays[] = $startOfWeek->copy();
            $startOfWeek->addDay();
        }

        return $weekDays;
    }
}
