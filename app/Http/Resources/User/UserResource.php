<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        // TODO return photo to user
        // if (isset($data['photo'])) {
        //     $data['photo'] = url('/') . '/storage/' . $data['photo'];
        // }

        $permissions = $request->user()->getAllPermissions()->pluck('name')->toArray();
        $data += ['permissions' => $permissions];

        $roles = $request->user()->roles()->pluck('name')->toArray();
        $data += ['roles' => $roles];
        return $data;
    }
}
