<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Validators\OrderValidator;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Interfaces\OrderRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    public function __construct(OrderRepositoryInterface $orders)
    {
        $this->middleware('auth');
        $this->orders = $orders;
    }

    public function create(Request $request)
    {
        $attributes = array_merge(['user_id' => Auth::user()->id], $request->input('order'));
        OrderValidator::validateCreate($attributes);

        $order = $this->orders->create($attributes);

        return new OrderResource($order);
    }

    public function update(Request $request)
    {
        OrderValidator::validateUpdate($request->input('order'));

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
