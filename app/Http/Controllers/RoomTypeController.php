<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomType\StoreRoomTypeRequest;
use App\Http\Requests\RoomType\UpdateRoomTypeRequest;
use App\Http\Resources\RoomType\RoomTypeCollection;
use App\Http\Resources\RoomType\RoomTypeResource;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);

//        $this->middleware(['permission:room_types.create'], ['only' => ['store']]);
//        $this->middleware(['permission:room_types.read'], ['only' => ['index', 'show']]);
//        $this->middleware(['permission:room_types.update'], ['only' => ['update']]);
//        $this->middleware(['permission:room_types.delete'], ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->all('search');
        $room_types = RoomType::filter($filters)->paginate();
        return new RoomTypeCollection($room_types);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomTypeRequest $request)
    {
        $room_type = RoomType::create($request->validated());
        return new RoomTypeResource($room_type);
    }

    /**
     * Display the specified resource.
     */
    public function show(RoomType $room_type)
    {
        return new RoomTypeResource($room_type);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomTypeRequest $request, RoomType $room_type)
    {
        $room_type->update($request->validated());
        return new RoomTypeResource($room_type);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomType $room_type)
    {
        $room_type->delete();
    }
}
