<?php

namespace App\Models;

use App\Traits\LogActivity;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CheckoutItem extends Pivot
{
    use LogActivity;

    public $incrementing = true;

    protected $fillable = ['item_id', 'checkout_id', 'rack_id', 'quantity', 'fullCheckout'];

    protected $table = 'checkout_item';
}
