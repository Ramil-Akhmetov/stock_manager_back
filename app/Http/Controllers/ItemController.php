<?php

namespace App\Http\Controllers;

use App\Events\ItemEvent;
use App\Http\Requests\Item\IndexItemRequest;
use App\Http\Requests\Item\StoreItemRequest;
use App\Http\Requests\Item\UpdateItemRequest;
use App\Http\Resources\Item\ItemCollection;
use App\Http\Resources\Item\ItemResource;
use App\Models\Checkin;
use App\Models\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);

        //        $this->middleware(['permission:items.create'], ['only' => ['store']]);
        //        $this->middleware(['permission:items.read'], ['only' => ['index', 'show']]);
        //        $this->middleware(['permission:items.update'], ['only' => ['update']]);
        //        $this->middleware(['permission:items.delete'], ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexItemRequest $request)
    {
        $validated = $request->validated();

        $limit = $validated['limit'] ?? 10;
        $search = $validated['search'] ?? null;
        $orderBy = $validated['orderBy'] ?? 'created_at';
        $order = $validated['order'] ?? 'desc';
        $room_id = $validated['room_id'] ?? null;
        $checkin_id = $validated['checkin_id'] ?? null;
        $code = $validated['code'] ?? null;
        $withTrashed = $validated['withTrashed'] ?? false;
        $only_mines = $validated['only_mines'] ?? false;

        if ($code) {
            $item = Item::where('code', $code)->first();
            return new ItemResource($item);
        }

        $query = Item::query();

        $isAdmin = $request->user()->whereHas('roles', function ($query) {
            $query->where('name', 'Администратор');
        })->first();

        if ($only_mines && !$isAdmin) {
            $query->whereHas('room', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            });
        }


        if ($withTrashed) {
            $query->withTrashed();
        }

        if ($search) {
            $query->where('code', 'like', '%' . $search . '%')
                ->orWhere('name', 'like', '%' . $search . '%')
                ->orWhere('quantity', 'like', '%' . $search . '%')
                ->orWhere('unit', 'like', '%' . $search . '%')
                ->orWhereHas('category', function (Builder $query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('room', function (Builder $query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('room.user', function (Builder $query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('surname', 'like', '%' . $search . '%')
                        ->orWhere('patronymic', 'like', '%' . $search . '%');
                });
        }
        if ($room_id) {
            $query->where('room_id', $room_id);
        }
        if ($checkin_id) {
            $i = Checkin::find($checkin_id)->items()->orderBy($orderBy, $order)->paginate($limit);
            return new ItemCollection($i);
            //            $query->whereHas('checkins', function (Builder $q) use ($checkin_id) {
            //                $q->where('id', $checkin_id);
            //            });
        }

        $items = $query->orderBy($orderBy, $order)->paginate($limit);
        return new ItemCollection($items);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request)
    {
        $validated = $request->validated();
        $item = DB::transaction(function () use ($validated) {
            $item = Item::create($validated);
            ItemEvent::dispatch($item);
            return $item;
        });
        return new ItemResource($item);
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        return new ItemResource($item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        $validated = $request->validated();
        $item = DB::transaction(function () use ($item, $validated) {
            $item->update($validated);
            //            ItemEvent::dispatch($item);
            return $item;
        });
        return new ItemResource($item);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();
    }
}
