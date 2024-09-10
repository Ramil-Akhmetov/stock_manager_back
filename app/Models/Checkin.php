<?php

namespace App\Models;

use App\Traits\LogActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

class Checkin extends Model
{
    use HasFactory, LogActivity;

    protected $fillable = ['note', 'user_id', 'supplier_id', 'extra_attributes', 'room_id'];

    protected $hidden = ['deleted_at'];

    protected $with = ['items', 'supplier', 'user', 'room'];

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
        return $this->belongsToMany(Item::class)
            ->using(CheckinItem::class)
            ->withPivot([
                'quantity',
                'rack_id',
            ])
            ->withTimestamps();
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
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
        $query->where('note', 'like', "%$s%");
    }
}
