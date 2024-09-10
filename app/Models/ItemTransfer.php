<?php

namespace App\Models;

use App\Traits\LogActivity;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ItemTransfer extends Pivot
{
    use LogActivity;

    public $incrementing = true;

    protected $fillable = ['item_id', 'transfer_id', 'to_rack_id', 'from_rack_id', 'newCode', 'quantity', 'fullTransfer'];

    protected $table = 'item_transfer';
    protected $with = ['fromRack', 'toRack'];


    public function fromRack()
    {
        return $this->belongsTo(Rack::class, 'from_rack_id');
    }

    public function toRack()
    {
        return $this->belongsTo(Rack::class, 'to_rack_id');
    }
}
