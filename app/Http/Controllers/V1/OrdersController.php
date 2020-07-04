<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::all();

        return response()->json($orders);
    }

    public function create(Request $request)
    {
        $order = new Order;

        $order->site = $request->site;
        $order->order_date = $request->order_date;

        $order->save();

        return response()->json($order);
    }

    public function show($id)
    {
        $order = Order::find($id);

        return response()->json($order);
    }

    public function update(Request $request, $id)
    {
        $order = Order::find($id);

        $order->site = $request->input('site');
        $order->order_date = $request->input('order_date');
        $order->save();
        return response()->json($order);
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        $order->delete();

        return response()->json('order removed successfully');
    }
}
