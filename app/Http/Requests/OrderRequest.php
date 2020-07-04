<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;

trait OrderRequest
{
    protected function validateCreate(Request $request)
    {
        $this->validate($request, [
            'order.site' => 'required',
            'order.order_date' => 'required',
        ]);
    }

    protected function validateUpdate(Request $request)
    {
        $this->validate($request, [
            'order.site' => 'sometimes',
            'order.order_date' => 'sometimes',
        ]);
    }
}
