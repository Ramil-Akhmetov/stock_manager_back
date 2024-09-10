<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as BasePermission;

class Permission extends BasePermission
{
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
