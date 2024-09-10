<?php

namespace App\Http\Controllers;

use App\Http\Requests\Responsibility\StoreResponsibilityRequest;
use App\Http\Requests\Responsibility\UpdateResponsibilityRequest;
use App\Http\Resources\Responsibility\ResponsibilityCollection;
use App\Http\Resources\Responsibility\ResponsibilityResource;
use App\Models\Responsibility;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ResponsibilityController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);

//        $this->middleware(['permission:responsibilities.create'], ['only' => ['store']]);
//        $this->middleware(['permission:responsibilities.read'], ['only' => ['index', 'show']]);
//        $this->middleware(['permission:responsibilities.update'], ['only' => ['update']]);
//        $this->middleware(['permission:responsibilities.delete'], ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $responsibilities = Responsibility::paginate();
        return new ResponsibilityCollection($responsibilities);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreResponsibilityRequest $request)
    {
        $validated = $request->validated();
        if (!isset($validated['start_date']) || $validated['start_date'] === null) {
            $validated += ['start_date' => Carbon::now()->toDateString()];
        }
        $responsibility = Responsibility::create($validated);
//        ResponsibilityEvent::dispatch($responsibility, 'store');
        return new ResponsibilityResource($responsibility);
    }

    /**
     * Display the specified resource.
     */
    public function show(Responsibility $responsibility)
    {
        return new ResponsibilityResource($responsibility);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateResponsibilityRequest $request, Responsibility $responsibility)
    {
        $responsibility->update($request->validated());
//        ResponsibilityEvent::dispatch($responsibility, 'store');
        return new ResponsibilityResource($responsibility);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Responsibility $responsibility)
    {
        $responsibility->delete();
    }
}
