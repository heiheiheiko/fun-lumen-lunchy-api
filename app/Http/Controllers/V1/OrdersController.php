<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Interfaces\OrderRepositoryInterface;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    use OrderRequest;

    public function __construct(OrderRepositoryInterface $orders)
    {
        $this->middleware('auth');
        $this->orders = $orders;
    }

    public function create(Request $request)
    {
        $this->validateCreate($request);

        $order = $this->orders->create($request)->input('order');

        return new OrderResource($order);
    }

    public function update(Request $request)
    {
        $this->validateUpdate($request);

        $order = $this->orders->update($request->input('order'));

        return new OrderResource($order);
    }

    public function show($id)
    {
        $order = $this->orders->find($id);

        return new OrderResource($order);
    }

    public function index()
    {
        $orders = $this->orders->all();

        return new OrderCollection($orders);
    }

    public function destroy($id)
    {
        $order = $this->orders->delete($id);

        return new OrderResource($order);
    }
}
