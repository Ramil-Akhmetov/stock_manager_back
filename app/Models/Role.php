<?php

namespace App\Models;

use App\Traits\LogActivity;
use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    //TODO doesn't show in properties logs
    use LogActivity;

    protected $with = ['permissions'];

    public function scopeFilter($query, array $filters)
    {
        if ($filters['search']) {
            $query->search($filters['search']);
        }
    }

    public function scopeSearch($query, $s)
    {
        $query->where('name', 'like', "%$s%");
    }
}
