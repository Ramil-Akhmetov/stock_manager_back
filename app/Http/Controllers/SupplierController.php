<?php

namespace App\Http\Controllers;

use App\Http\Requests\Supplier\StoreSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use App\Http\Resources\Supplier\SupplierCollection;
use App\Http\Resources\Supplier\SupplierResource;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);

//        $this->middleware(['permission:suppliers.create'], ['only' => ['store']]);
//        $this->middleware(['permission:suppliers.read'], ['only' => ['index', 'show']]);
//        $this->middleware(['permission:suppliers.update'], ['only' => ['update']]);
//        $this->middleware(['permission:suppliers.delete'], ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validated = $request->all();

        $limit = $validated['limit'] ?? Supplier::all()->count();
        $search = $validated['search'] ?? null;
        $orderBy = $validated['orderBy'] ?? 'created_at';
        $order = $validated['order'] ?? 'desc';

        $query = Supplier::query();
        if ($search) {
            $query->where('surname', 'like', '%' . $search . '%')
                ->orWhere('name', 'like', '%' . $search . '%')
                ->orWhere('patronymic', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('company', 'like', '%' . $search . '%');
        }

        $suppliers = $query->orderBy($orderBy, $order)->paginate($limit);
        return new SupplierCollection($suppliers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request)
    {
        $supplier = Supplier::create($request->validated());
        return new SupplierResource($supplier);
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        return new SupplierResource($supplier);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $supplier->update($request->validated());
        return new SupplierResource($supplier);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
    }
}
