<?php

namespace App\Models;

use App\Traits\LogActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

class Item extends Model
{
    use HasFactory, LogActivity, SoftDeletes;

    protected $fillable = ['code', 'name', 'quantity', 'unit', 'category_id', 'type_id', 'room_id', 'extra_attributes'];

    protected $hidden = ['deleted_at'];

    public $casts = [
        'extra_attributes' => SchemalessAttributes::class,
    ];

    protected $with = ['category', 'type', 'room', 'rack', 'lastConfirmation'];

    //region Relationships

    public function from_rack()
    {
        return $this->belongsTo(Rack::class, 'pivot_from_rack_id');
    }

    public function to_rack()
    {
        return $this->belongsTo(Rack::class, 'pivot_to_rack_id');
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function rack()
    {
        return $this->belongsTo(\App\Models\Rack::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function checkins()
    {
        return $this->belongsToMany(Checkin::class)
            ->using(CheckinItem::class)
            ->withPivot([
                'quantity',
                'rack_id',
            ])
            ->withTimestamps();
    }

    public function checkouts()
    {
        return $this->belongsToMany(Checkout::class)
            ->using(CheckoutItem::class)
            ->withPivot([
                'quantity',
            ])
            ->withTimestamps();
    }

    public function transfers()
    {
        return $this->belongsToMany(Transfer::class)
            ->using(ItemTransfer::class)
            ->withPivot([
                'fullTransfer',
                'from_rack_id',
                'to_rack_id',
                'newCode',
                'quantity',
            ])
            ->withTimestamps()
            ->with(['pivot.fromRack', 'pivot.toRack']);
    }

    // Custom relationship to the pivot table
    public function itemTransfers()
    {
        return $this->hasMany(ItemTransfer::class);
    }

    public function confirmations()
    {
        return $this->hasMany(Confirmation::class);
    }
    //endregion

    public function scopeWithExtraAttributes()
    {
        return $this->extra_attributes->modelScope();
    }

    //TODO filter
    public function scopeFilter($query, array $filters)
    {
        if ($filters['search']) {
            $query->search($filters['search']);
        }
        if ($filters['category_id']) {
            $query->where('category_id', $filters['category_id']);
        }
        if ($filters['type_id']) {
            $query->where('type_id', $filters['type_id']);
        }
    }

    public function scopeSearch($query, $s)
    {
        $query->where('code', 'like', "%$s%")
            ->orWhere('name', 'like', "%$s%");
    }

    public function lastConfirmation()
    {
        return $this->hasOne(Confirmation::class)
            ->with('user')
            ->latest();
    }
}
