<?php

namespace App\Models;

use App\Traits\LogActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

class Transfer extends Model
{
    use HasFactory, LogActivity;

//    protected $guarded = false;

    protected $fillable = ['user_id', 'from_room_id', 'to_room_id', 'transfer_status_id', 'reason', 'extra_attributes'];

    protected $hidden = ['deleted_at'];

    protected $with = ['items', 'transfer_status', 'user', 'items', 'from_room', 'to_room'];

    public $casts = [
        'extra_attributes' => SchemalessAttributes::class,
    ];

    public function scopeWithExtraAttributes()
    {
        return $this->extra_attributes->modelScope();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transfer_status()
    {
        return $this->belongsTo(TransferStatus::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class)
            ->using(ItemTransfer::class)
            ->withPivot([
                'fullTransfer',
                'from_rack_id',
                'to_rack_id',
                'newCode',
                'quantity',
            ])
            ->withTimestamps();
    }

    public function from_room()
    {
        return $this->belongsTo(Room::class);
    }

    public function to_room()
    {
        return $this->belongsTo(Room::class);
    }

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
