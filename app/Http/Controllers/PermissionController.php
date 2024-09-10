<?php

namespace App\Http\Controllers;

use App\Http\Resources\Permission\PermissionCollection;
use App\Http\Resources\Permission\PermissionResource;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);

//        $this->middleware(['permission:permissions.read'], ['only' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->all('search');
        $permissions = Permission::filter($filters)->paginate();
        return new PermissionCollection($permissions);
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        return new PermissionResource($permission);
    }
}
