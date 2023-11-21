<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class orderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $arr = [];
        foreach($this->products as $product){
            $arr[] = Product::find($product);
        }
        return [
            'id' => $this->id,
            'user_name' => User::find($this->users_id)->name,
            'time' => Carbon::parse($this->created_at)->format('d.m.Y'),
            'products' => $arr,
            'status' => $this->status,
            'order_price' => $this->order_price
        ];
    }
}
