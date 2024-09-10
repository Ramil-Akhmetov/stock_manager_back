<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Models\Activity as ActivityModel;

class Activity extends ActivityModel
{
    public $with = ['causer'];

    public function scopeFilter($query, array $filters)
    {
        if ($filters['search']) {
            $query->search($filters['search']);
        }
    }

    public function scopeSearch($query, $s)
    {
        $query->where('log_name', 'like', "%$s%")
            ->orWhere('description', 'like', "%$s%")
            ->orWhere('subject_type', 'like', "%$s%");
    }
}
