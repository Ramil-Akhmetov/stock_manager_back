<?php

namespace App\Traits;

use ReflectionClass;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

trait LogActivity
{
    use LogsActivity;

    public function getDescriptionForEvent($event)
    {
        $log_name = static::getLogNameToUse();
        return "$log_name has been $event.";
    }

    protected static function getLogNameToUse()
    {
        return (new ReflectionClass(static::class))->getShortName();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }
}
