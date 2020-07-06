<?php

namespace App\Repositories;

use App\Interfaces\OrderRepositoryInterface;
use App\Order;
use Illuminate\Http\Request;

class OrderRepository implements OrderRepositoryInterface
{
    public function create(Request $request)
    {
        return Order::create($request->input('order'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update($request->input('order'));
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
