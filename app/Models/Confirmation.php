<?php

namespace App\Models;

use App\Traits\LogActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

class Confirmation extends Model
{
    use HasFactory, LogActivity;

    protected $fillable = ['item_id', 'quantity', 'user_id', 'extra_attributes'];

    protected $hidden = ['deleted_at'];

    public $casts = ['extra_attributes' => SchemalessAttributes::class];

    public function scopeWithExtraAttributes()
    {
        return $this->extra_attributes->modelScope();
    }

    //region Relationships
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //endregion
}
