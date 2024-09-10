<?php

namespace App\Http\Controllers;

use App\Http\Requests\Activity\IndexActivityRequest;
use App\Models\Activity;
use Illuminate\Http\Request;
use App\Http\Resources\Activity\ActivityResource;
use App\Http\Resources\Activity\ActivityCollection;

class ActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);

//        $this->middleware(['permission:activities.read'], ['only' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexActivityRequest $request)
    {
        $validated = $request->validated();

        $limit = isset($validated['limit']) ? $validated['limit'] : 10;
        // TODO add search filter
//       $filters = $validated['search'];
        $orderBy = isset($validated['orderBy']) ? $validated['orderBy'] : 'created_at';
        $order = isset($validated['order']) ? $validated['order'] : 'desc';

        $activities = Activity::orderBy($orderBy, $order)->paginate($limit);
        return new ActivityCollection($activities);
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {
        return new ActivityResource($activity);
    }
}
