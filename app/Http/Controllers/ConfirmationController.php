<?php

namespace App\Http\Controllers;

use App\Events\ConfirmationEvent;
use App\Http\Requests\Confirmaiton\StoreConfirmationRequest;
use App\Http\Requests\Confirmaiton\UpdateConfirmationRequest;
use App\Http\Resources\Confirmation\ConfirmationCollection;
use App\Http\Resources\Confirmation\ConfirmationResource;
use App\Models\Confirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ConfirmationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);

//        $this->middleware(['permission:confirmations.create'], ['only' => ['store']]);
//        $this->middleware(['permission:confirmations.read'], ['only' => ['index', 'show']]);
//        $this->middleware(['permission:confirmations.update'], ['only' => ['update']]);
//        $this->middleware(['permission:confirmations.delete'], ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $confirmations = Confirmation::paginate();
        return new ConfirmationCollection($confirmations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConfirmationRequest $request)
    {
        $validated = $request->validated();
        $validated += [
            'user_id' => $request->user()->id,
        ];
        $confirmation = DB::transaction(function () use ($validated) {
            $confirmation = Confirmation::create($validated);
//            ConfirmationEvent::dispatch($confirmation, 'store');
            return $confirmation;
        });
        return new ConfirmationResource($confirmation);
    }

    /**
     * Display the specified resource.
     */
    public function show(Confirmation $confirmation)
    {
        return new ConfirmationResource($confirmation);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateConfirmationRequest $request, Confirmation $confirmation)
    {
        $validated = $request->validated();
        $confirmation = DB::transaction(function () use ($confirmation, $validated) {
            $confirmation->update($validated);
            ConfirmationEvent::dispatch($confirmation, 'store');
            return $confirmation;
        });
        return new ConfirmationResource($confirmation);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Confirmation $confirmation)
    {
        $confirmation->delete();
    }
}
