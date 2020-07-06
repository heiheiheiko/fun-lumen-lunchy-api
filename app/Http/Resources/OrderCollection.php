<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\Order;

class OrderCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function (Order $order) {
            return (new OrderResource($order));
        });
    }
}
