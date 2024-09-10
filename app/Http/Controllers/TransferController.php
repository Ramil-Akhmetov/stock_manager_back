<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transfer\StoreTransferRequest;
use App\Http\Requests\Transfer\UpdateTransferRequest;
use App\Http\Resources\Transfer\TransferCollection;
use App\Http\Resources\Transfer\TransferResource;
use App\Http\Resources\TransferStatus\TransferStatusCollection;
use App\Http\Resources\TransferStatus\TransferStatusResource;
use App\Models\Item;
use App\Models\Rack;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Bridge\FormatsScopesForStorage;

class TransferController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);

//        $this->middleware(['permission:transfers.create'], ['only' => ['store']]);
//        $this->middleware(['permission:transfers.read'], ['only' => ['index', 'show']]);
//        $this->middleware(['permission:transfers.update'], ['only' => ['update']]);
//        $this->middleware(['permission:transfers.delete'], ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validated = $request->all();
        $limit = $validated['limit'] ?? 10;
        $search = $validated['search'] ?? null;
        $orderBy = $validated['orderBy'] ?? 'created_at';
        $order = $validated['order'] ?? 'desc';
        $transfer_status_id = $validated['transfer_status_id'] ?? null;
        $only_mines = $validated['only_mines'] ?? null;
//        $from_room_id = $validated['from_room_id'] ?? null;

        $query = Transfer::query();

        if ($request->user()->roles()->first()->name != 'Администратор') {
            $query->where('user_id', $request->user()->id);
        }

        if ($only_mines) {
            $query->where('user_id', $request->user()->id);
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($transfer_status_id) {
            $query->where('transfer_status_id', $transfer_status_id);
        }

        $transfers = $query->orderBy($orderBy, $order)->paginate($limit);

        foreach ($transfers as $transfer) {
            foreach ($transfer->items as $item) {
                $fromRack = Rack::find($item->pivot->from_rack_id);
                $toRack = Rack::find($item->pivot->to_rack_id);

                // Add additional data to the pivot array
                $item->pivot->fromRack = $fromRack ? $fromRack->toArray() : null;
                $item->pivot->toRack = $toRack ? $toRack->toArray() : null;
            }
        }

        return new TransferCollection($transfers);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransferRequest $request)
    {
        $validated = $request->validated();
        $validated += [
            'user_id' => $request->user()->id,
            'transfer_status_id' => 1
        ];

        $transfer = DB::transaction(function () use ($validated) {
            $transfer = Transfer::create($validated);

            foreach ($validated['items'] as $item) {
                $db_item = Item::find($item['id']);
                if ($item['fullTransfer'] == true) {
                    $transfer->items()->attach($item['id'], [
                        'fullTransfer' => true,
                        'from_rack_id' => $db_item->rack_id,
                        'quantity' => $db_item->quantity,
                        'to_rack_id' => $item['to_rack_id'],
                    ]);
                } else {
                    $transfer->items()->attach($item['id'], [
                        'fullTransfer' => false,
                        'from_rack_id' => $db_item->rack_id,
                        'quantity' => $item['quantity'],
                        'to_rack_id' => $item['to_rack_id'],
                        'newCode' => $item['newCode'],
                    ]);
                }
            }
            return $transfer;
        });

        $t = Transfer::find($transfer->id);

        return new TransferResource($t);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transfer $transfer)
    {
        $transferArray = $transfer->toArray();

        foreach ($transfer->items as $item) {
            $fromRack = Rack::find($item->pivot->from_rack_id);
            $toRack = Rack::find($item->pivot->to_rack_id);

            // Add additional data to the pivot array
            $itemArray = $item->toArray();
            $itemArray['pivot']['fromRack'] = $fromRack ? $fromRack->toArray() : null;
            $itemArray['pivot']['toRack'] = $toRack ? $toRack->toArray() : null;

            // Update the transfer array with the modified item data
            foreach ($transferArray['items'] as &$transferItem) {
                if ($transferItem['id'] == $item->id) {
                    $transferItem = $itemArray;
                    break;
                }
            }
        }

        return new TransferResource($transferArray);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatetransferRequest $request, Transfer $transfer)
    {
        //TODO add update
        $transfer->update($request->validated());
        return new TransferResource($transfer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transfer $transfer)
    {
        $transfer->delete();
    }

    public function changeStatus(Transfer $transfer)
    {
        $tran = DB::transaction(function () use ($transfer) {
            $transfer->transfer_status_id = request('transfer_status_id');
            $transfer->save();

            $t = Transfer::find($transfer->id);

            if (request('transfer_status_id') == 3) {
                $items = $t->items;
                foreach ($items as $item) {
                    if ($item->pivot->fullTransfer == true) {
                        $item->room_id = $t->to_room_id;
                        $item->rack_id = $item->pivot->to_rack_id;
                        $item->save();
                    } else {
                        $item->quantity = $item->quantity - $item->pivot->quantity;
                        $item->save();

                        $newItem = Item::create([
                            'code' => $item->pivot->newCode,
                            'name' => $item->name,
                            'quantity' => $item->pivot->quantity,
                            'unit' => $item->unit,
                            'category_id' => $item->category_id,
                            'type_id' => $item->type_id,
                            'room_id' => $t->to_room_id,
                            'rack_id' => $item->pivot->to_rack_id,
                        ]);
                    }
                }
            }
            return $t;
        });


        return new TransferResource($tran);
    }
}
