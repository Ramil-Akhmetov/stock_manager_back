<?php

namespace App\Http\Controllers;

use App\Http\Requests\Checkin\StoreCheckinRequest;
use App\Http\Requests\Checkin\UpdateCheckinRequest;
use App\Http\Resources\Checkin\CheckinCollection;
use App\Http\Resources\Checkin\CheckinResource;
use App\Models\Checkin;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckinController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);

//        $this->middleware(['permission:checkins.create'], ['only' => ['store']]);
//        $this->middleware(['permission:checkins.read'], ['only' => ['index', 'show']]);
//        $this->middleware(['permission:checkins.update'], ['only' => ['update']]);
//        $this->middleware(['permission:checkins.delete'], ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
//        $filters = $request->all('search');
        $checkins = Checkin::paginate();
        return new CheckinCollection($checkins);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCheckinRequest $request)
    {
        $validated = $request->validated();
        $validated += [
            'user_id' => $request->user()->id,
        ];

        $checkin = DB::transaction(function () use ($validated) {
            $checkin = Checkin::create($validated);

            foreach ($validated['items'] as $item) {
                $item += [
                    'room_id' => $validated['room_id'],
                ];
                $db_item = Item::create($item);
                $checkin->items()->attach($db_item->id, [
                    'quantity' => $db_item->quantity,
                ]);
            }
            return $checkin;
        });

        return new CheckinResource($checkin);
    }

    /**
     * Display the specified resource.
     */
    public function show(Checkin $checkin)
    {
        return new CheckinResource($checkin);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCheckinRequest $request, Checkin $checkin)
    {
        $validated = $request->validated();

        $validated += [
            'user_id' => $request->user()->id,
        ];

//        foreach ($validated['items'] as $item) {
//            $item += [
//                'room_id' => $validated['room_id'],
//            ];
//            $existed_db_item = Item::where('id', $item['id'])->first();
//            return response()->json([
//                'validated' => $existed_db_item,
//            ]);
//            if ($existed_db_item->checkins->where('checkin_id', $checkin->id)->count() == 0) {
//                $new_item = Item::create($item);
//                $checkin->items()->attach($new_item->id, [
//                    'quantity' => $new_item->quantity,
//                ]);
//            } else {
//                $checkin->items()->updateExistingPivot($item['id'], [
//                    'quantity' => $item['quantity'],
//                ]);
//            }
//        }


        $updated_checkin = DB::transaction(function () use ($checkin, $validated) {
            $checkin->update($validated);
            foreach ($validated['items'] as $item) {
                $item += [
                    'room_id' => $validated['room_id'],
                ];
                if (!isset($item['id'])) {
                    $new_item = Item::create($item);
                    $checkin->items()->attach($new_item->id, [
                        'quantity' => $new_item->quantity,
                    ]);
                } else {
                    $existed_db_item = Item::where('id', $item['id'])->first();
                    $existed_db_item->update($item);
                    $checkin->items()->updateExistingPivot($item['id'], [
                        'quantity' => $item['quantity'],
                    ]);
                }
//                $existed_db_item = Item::where('id', $item['id'])->first();
//
//                if ($existed_db_item->checkins()->where('checkin_id', $checkin->id)->count() == 0) {
//                    $new_item = Item::create($item);
//                    $checkin->items()->attach($new_item->id, [
//                        'quantity' => $new_item->quantity,
//                    ]);
//                } else {
//                    $existed_db_item->update($item);
//                    $checkin->items()->updateExistingPivot($item['id'], [
//                        'quantity' => $item['quantity'],
//                    ]);
//                }
            }
            return $checkin;
        });
        return new CheckinResource($updated_checkin);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Checkin $checkin)
    {
        $checkin->delete();
    }
}
