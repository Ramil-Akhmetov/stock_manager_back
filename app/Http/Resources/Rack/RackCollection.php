<?php

namespace App\Http\Resources\Rack;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RackCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
