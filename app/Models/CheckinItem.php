<?php

namespace App\Models;

use App\Traits\LogActivity;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CheckinItem extends Pivot
{
    use LogActivity;

    public $incrementing = true;

    protected $fillable = ['item_id', 'checkin_id', 'quantity'];

    protected $table = 'checkin_item';
}
