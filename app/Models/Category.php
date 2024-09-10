<?php

namespace App\Models;

use App\Traits\LogActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

class Category extends Model
{
    use HasFactory, LogActivity;

    protected $fillable = ['code', 'name', 'extra_attributes'];

    public $casts = [
        'extra_attributes' => SchemalessAttributes::class,
    ];

    public function scopeWithExtraAttributes()
    {
        return $this->extra_attributes->modelScope();
    }

    //region Relationships
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    //endregion

    public function scopeFilter($query, array $filters)
    {
        if ($filters['search']) {
            $query->search($filters['search']);
        }
    }

    public function scopeSearch($query, $s)
    {
        $query->where('code', 'like', "%$s%")
            ->orWhere('name', 'like', "%$s%");
    }
}
