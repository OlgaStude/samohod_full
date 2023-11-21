<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class productRecourse extends JsonResource
{
    public static $wrap = 'content';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'img' => $this->img,
            'categories_id' => $this->categories_id
        ];
    }
}
