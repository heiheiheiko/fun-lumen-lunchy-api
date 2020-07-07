<?php

namespace App\Repositories;

use App\Interfaces\OrderRepositoryInterface;
use App\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{
    public function create($attributes)
    {
        return Order::create($attributes);
    }

    public function update($attributes)
    {
        $order = Order::findOrFail($attributes['id']);
        $order->update($attributes);
        return $order;
    }

    public function all()
    {
        return Order::all();
    }

    public function find($id)
    {
        return Order::findOrFail($id);
    }

    public function delete($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return $order;
    }
}
