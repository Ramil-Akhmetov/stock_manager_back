<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Requests\StoreInviteCodeRequest;
use App\Http\Requests\UpdateInviteCodeRequest;
use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\InviteCode\InviteCodeCollection;
use App\Http\Resources\InviteCode\InviteCodeResource;
use App\Models\Category;
use App\Models\InviteCode;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InviteCodeController extends Controller
{
    public static function generateCode()
    {
        do {
            $code = Str::random(10);
        } while (InviteCode::where('code', $code)->first());

        return $code;
    }


//    public function __construct()
//    {
//        $this->middleware(['auth:api']);
//
//        $this->middleware(['permission:categories.create'], ['only' => ['store']]);
//        $this->middleware(['permission:categories.read'], ['only' => ['index', 'show']]);
//        $this->middleware(['permission:categories.update'], ['only' => ['update']]);
//        $this->middleware(['permission:categories.delete'], ['only' => ['destroy']]);
//    }

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

        $query = InviteCode::query();
        if ($search) {
            $query->where('code', 'like', '%' . $search . '%')
                ->orWhereHas('user', function (Builder $query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('user', function (Builder $query) use ($search) {
                    $query->where('surname', 'like', '%' . $search . '%');
                })
                ->orWhereHas('user', function (Builder $query) use ($search) {
                    $query->where('patronymic', 'like', '%' . $search . '%');
                });
        }
        $invite_codes = $query->with(['user' => function ($q) use ($order, $orderBy) {
            if ($orderBy == 'name') {
                $q->orderBy('name', $order);
            } elseif ($orderBy == 'surname') {
                $q->orderBy('surname', $order);
            } elseif ($orderBy == 'patronymic') {
                $q->orderBy('patronymic', $order);
            }
        }]);

        if ($orderBy == 'code') {
            $invite_codes = $invite_codes->orderBy($orderBy, $order);
        }

        $invite_codes = $invite_codes->paginate($limit);


        return new InviteCodeCollection($invite_codes);
    }

    /**
     * Display the specified resource.
     */
    public function show(InviteCode $invite_code)
    {
        return new InviteCodeResource($invite_code);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InviteCode $invite_code)
    {
        $invite_code->delete();
    }
}
