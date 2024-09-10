<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRackRequest;
use App\Http\Requests\UpdateRackRequest;
use App\Http\Resources\Rack\RackCollection;
use App\Http\Resources\Rack\RackResource;
use App\Models\Rack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RackController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);

//        $this->middleware(['permission:racks.create'], ['only' => ['store']]);
//        $this->middleware(['permission:racks.read'], ['only' => ['index', 'show']]);
//        $this->middleware(['permission:racks.update'], ['only' => ['update']]);
//        $this->middleware(['permission:racks.delete'], ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validated = $request->all();

        $limit = $validated['limit'] ?? Rack::all()->count();
        $search = $validated['search'] ?? null;
        $orderBy = $validated['orderBy'] ?? 'created_at';
        $order = $validated['order'] ?? 'desc';
        $room_id = $validated['room_id'] ?? null;

        $user = $request->user();
        $query = Rack::query();

        if ($room_id) {
            $query->where('room_id', $room_id);
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $racks = $query->orderBy($orderBy, $order)->paginate($limit);
        return new RackCollection($racks);
    }

//    /**
//     * Store a newly created resource in storage.
//     */
//    public function store(StoreRackRequest $request)
//    {
//        $validated = $request->validated();
//
//        $rack = DB::transaction(function () use ($validated) {
//            $rack = Rack::create($validated);
//
//            foreach ($validated['racks'] as $rack) {
//                $new_rack = Rack::create([
//                    'name' => $rack['name'],
//                    'rack_id' => $rack->id,
//                ]);
//            }
//            return $rack;
//        });
//
//        $r = Rack::where('id', $rack->id)->first();
////        RackEvent::dispatch($rack, 'store');
//        return new RackResource($r);
//    }
//
//    /**
//     * Display the specified resource.
//     */
    public function show(Rack $rack)
    {
        return new RackResource($rack);
    }
//
//    /**
//     * Update the specified resource in storage.
//     */
//    public function update(UpdateRackRequest $request, Rack $rack)
//    {
//        $old_user_id = $rack->user_id;
//        $rack->update($request->validated());
//        RackEvent::dispatch($rack, 'update', $old_user_id);
//        return new RackResource($rack);
//    }
//
//    /**
//     * Remove the specified resource from storage.
//     */
//    public function destroy(Rack $rack)
//    {
//        $rack->delete();
//    }
}
