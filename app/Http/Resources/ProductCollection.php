<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($product){
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $product->quantity,
                    'stock_price' => $product->stock_price,
                    'sell_price' => $product->sell_price,
                ];
            })
        ];
    }
}
