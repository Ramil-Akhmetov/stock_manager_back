<?php

namespace App\Http\Controllers;

use App\Http\Requests\Checkout\StoreCheckoutRequest;
use App\Http\Requests\Checkout\UpdateCheckoutRequest;
use App\Http\Resources\Checkout\CheckoutCollection;
use App\Http\Resources\Checkout\CheckoutResource;
use App\Models\Checkout;
use App\Models\Item;
use App\Models\Rack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);

//        $this->middleware(['permission:checkouts.create'], ['only' => ['store']]);
//        $this->middleware(['permission:checkouts.read'], ['only' => ['index', 'show']]);
//        $this->middleware(['permission:checkouts.update'], ['only' => ['update']]);
//        $this->middleware(['permission:checkouts.delete'], ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
//        $filters = $request->all('search');
        $checkouts = Checkout::paginate();
        return new CheckoutCollection($checkouts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCheckoutRequest $request)
    {
        $validated = $request->validated();
        $validated += [
            'user_id' => $request->user()->id,
        ];

//        $transfer = DB::transaction(function () use ($validated) {
//            $transfer = Transfer::create($validated);
//
//            foreach ($validated['items'] as $item) {
//                $db_item = Item::find($item['id']);
//                if ($item['fullTransfer'] == true) {
//                    $transfer->items()->attach($item['id'], [
//                        'fullTransfer' => true,
//                        'from_rack_id' => $db_item->rack_id,
//                        'quantity' => $db_item->quantity,
//                        'to_rack_id' => $item['to_rack_id'],
//                    ]);
//                } else {
//                    $transfer->items()->attach($item['id'], [
//                        'fullTransfer' => false,
//                        'from_rack_id' => $db_item->rack_id,
//                        'quantity' => $item['quantity'],
//                        'to_rack_id' => $item['to_rack_id'],
//                        'newCode' => $item['newCode'],
//                    ]);
//                }
//            }
//            return $transfer;
//        });

//        $t = Transfer::find($transfer->id);

        $checkout = DB::transaction(function () use ($validated) {
            $checkout = Checkout::create($validated);

            foreach ($validated['items'] as $item) {
                $db_item = Item::find($item['id']);

                if ($item['fullCheckout'] == true) {
                    $checkout->items()->attach($db_item->id, [
                        'fullCheckout' => true,
                        'rack_id' => $db_item->rack_id,
                        'quantity' => $db_item->quantity,
                    ]);
                    $db_item->delete();
                } else {
                    $checkout->items()->attach($db_item->id, [
                        'fullCheckout' => false,
                        'rack_id' => $db_item->rack_id,
                        'quantity' => $item['quantity'],
                    ]);

                    $i = $checkout->items()->find($db_item->id);
                    $db_item->delete();
                    $newItem = Item::create([
                        'code' => $i->code,
                        'name' => $i->name,
                        'quantity' => $db_item->quantity - $i->pivot->quantity,
                        'unit' => $i->unit,
                        'category_id' => $i->category_id,
                        'type_id' => $i->type_id,
                        'room_id' => $checkout->room_id,
                        'rack_id' => $i->rack_id,
                    ]);
                }
            }
            return $checkout;
        });
        return new CheckoutResource($checkout);
    }

    /**
     * Display the specified resource.
     */
    public function show(Checkout $checkout)
    {

        $checkoutArray = $checkout->toArray();

        foreach ($checkout->items()->withTrashed()->get() as $item) {
            $rack = Rack::find($item->pivot->rack_id);

            // Add additional data to the pivot array
            $itemArray = $item->toArray();
            $itemArray['pivot']['rack'] = $rack ? $rack->toArray() : null;

            // Update the transfer array with the modified item data
            foreach ($checkoutArray['items'] as &$transferItem) {
                if ($transferItem['id'] == $item->id) {
                    $transferItem = $itemArray;
                    break;
                }
            }
        }

        return new CheckoutResource($checkoutArray);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatecheckoutRequest $request, Checkout $checkout)
    {
        //TODO add update
        $checkout->update($request->validated());
        return new CheckoutResource($checkout);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Checkout $checkout)
    {
        $checkout->delete();
    }
}
