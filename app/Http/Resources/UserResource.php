<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        $orders = $this->orders()->get()->map(function (Order $order) {
            return (new OrderResource($order));
        });

        return [
            'id' => $this->id,
            'email' => $this->email,
            'username' => $this->username,
            'orders' => $orders,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
