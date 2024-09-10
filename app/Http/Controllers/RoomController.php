<?php

namespace App\Http\Controllers;

use App\Events\RoomEvent;
use App\Http\Requests\Room\StoreRoomRequest;
use App\Http\Requests\Room\UpdateRoomRequest;
use App\Http\Resources\Room\RoomCollection;
use App\Http\Resources\Room\RoomResource;
use App\Models\Rack;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);

        //        $this->middleware(['permission:rooms.create'], ['only' => ['store']]);
        //        $this->middleware(['permission:rooms.read'], ['only' => ['index', 'show']]);
        //        $this->middleware(['permission:rooms.update'], ['only' => ['update']]);
        //        $this->middleware(['permission:rooms.delete'], ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validated = $request->all();

        $limit = $validated['limit'] ?? Room::all()->count();
        $search = $validated['search'] ?? null;
        $orderBy = $validated['orderBy'] ?? 'created_at';
        $order = $validated['order'] ?? 'desc';
        $only_mines = $validated['only_mines'] ?? false;

        $user = $request->user();
        $query = Room::query();

        $isAdmin = User::where('id', $user->id)->whereHas('roles', function ($query) {
            $query->where('name', 'Администратор');
        })->first();

        if ($only_mines && !$isAdmin) {
            $query->where('user_id', $user->id);
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('number', 'like', '%' . $search . '%')
                ->orWhereHas('room_type', function (Builder $query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('user', function (Builder $query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('surname', 'like', '%' . $search . '%')
                        ->orWhere('patronymic', 'like', '%' . $search . '%');
                });
        }

        $rooms = $query->orderBy($orderBy, $order)->paginate($limit);
        return new RoomCollection($rooms);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomRequest $request)
    {
        $validated = $request->validated();

        // return response()->json([
        //     'data' => $validated,
        // ], 400);

        $room = DB::transaction(function () use ($validated) {
            $room = Room::create($validated);

            foreach ($validated['racks'] as $rack) {
                $new_rack = Rack::create([
                    'name' => $rack['name'],
                    'room_id' => $room->id,
                ]);
            }
            return $room;
        });

        $r = Room::where('id', $room->id)->first();
        //        RoomEvent::dispatch($room, 'store');
        return new RoomResource($r);
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        return new RoomResource($room);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomRequest $request, Room $room)
    {
        $validated = $request->validated();

        $room = DB::transaction(function () use ($room, $validated) {
            $room->update($validated);


            foreach ($validated['racks'] as $rack) {
                if (isset($rack['id'])) {
                    $new_rack = Rack::where('id', $rack['id'])->first();
                    $new_rack->update([
                        'name' => $rack['name'],
                    ]);
                } else {
                    $new_rack = Rack::create([
                        'name' => $rack['name'],
                        'room_id' => $room->id,
                    ]);
                }
            }
            return $room;
        });

        $r = Room::where('id', $room->id)->first();
        return new RoomResource($r);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $room->racks()->delete();
        $room->delete();
    }
}
