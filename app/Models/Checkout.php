<?php

namespace App\Models;

use App\Traits\LogActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

class Checkout extends Model
{
    use HasFactory, LogActivity;

    protected $fillable = ['note', 'user_id', 'extra_attributes', 'room_id'];

    protected $hidden = ['deleted_at'];

    protected $with = ['room', 'items', 'user'];

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
            ->using(CheckoutItem::class)
            ->withPivot([
                'fullCheckout',
                'rack_id',
                'quantity',
            ])
            ->withTrashed()
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

    //endregion

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
