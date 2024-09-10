<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransferStatusRequest;
use App\Http\Requests\UpdateTransferStatusRequest;
use App\Http\Resources\TransferStatus\TransferStatusCollection;
use App\Models\TransferStatus;
use Illuminate\Http\Request;

class TransferStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);

//        $this->middleware(['permission:transfers_status.create'], ['only' => ['store']]);
//        $this->middleware(['permission:transfers_status.read'], ['only' => ['index', 'show']]);
//        $this->middleware(['permission:transfers_status.update'], ['only' => ['update']]);
//        $this->middleware(['permission:transfers_status.delete'], ['only' => ['destroy']]);
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

        $query = TransferStatus::query();
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $transfers = $query->orderBy($orderBy, $order)->paginate($limit);

        return new TransferStatusCollection($transfers);
    }
}
