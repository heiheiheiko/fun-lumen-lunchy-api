<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $orders = Order::all();
        return new OrderCollection($orders);
    }

    public function create(Request $request)
    {
        $order = new Order;
        $order->site = $request->site;
        $order->order_date = $request->order_date;
        $order->save();

        return new OrderResource($order);
    }

    public function show($id)
    {
        $order = Order::find($id);

        return new OrderResource($order);
    }

    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        $order->site = $request->input('site');
        $order->order_date = $request->input('order_date');
        $order->save();

        return new OrderResource($order);
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        $order->delete();
    }
}
