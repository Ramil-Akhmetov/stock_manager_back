<?php

namespace App\Http\Controllers;

use App\Http\Requests\Type\StoreTypeRequest;
use App\Http\Requests\Type\UpdateTypeRequest;
use App\Http\Resources\Type\TypeCollection;
use App\Http\Resources\Type\TypeResource;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);

//        $this->middleware(['permission:types.create'], ['only' => ['store']]);
//        $this->middleware(['permission:types.read'], ['only' => ['index', 'show']]);
//        $this->middleware(['permission:types.update'], ['only' => ['update']]);
//        $this->middleware(['permission:types.delete'], ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validated = $request->all();

        $limit = $validated['limit'] ?? Type::all()->count();
        $search = $validated['search'] ?? null;
        $orderBy = $validated['orderBy'] ?? 'created_at';
        $order = $validated['order'] ?? 'desc';

        $query = Type::query();
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $types = $query->orderBy($orderBy, $order)->paginate($limit);
        return new TypeCollection($types);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTypeRequest $request)
    {
        $type = Type::create($request->validated());
        return new TypeResource($type);
    }

    /**
     * Display the specified resource.
     */
    public function show(Type $type)
    {
        return new TypeResource($type);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTypeRequest $request, Type $type)
    {
        $type->update($request->validated());
        return new TypeResource($type);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Type $type)
    {
        $type->delete();
    }
}
