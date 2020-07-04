<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    use OrderRequest;

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
        $this->validateCreate($request);

        $order = Order::create([
            'site' => $request->input('order.site'),
            'order_date' => $request->input('order.order_date'),
        ]);

        return new OrderResource($order);
    }

    public function show($id)
    {
        $order = Order::find($id);

        return new OrderResource($order);
    }

    public function update(Request $request, $id)
    {
        $this->validateUpdate($request);

        $order = Order::find($id);
        $order->update($request->get('order'));

        return new OrderResource($order);
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        $order->delete();
    }
}
