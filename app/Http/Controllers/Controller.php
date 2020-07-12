<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    protected function respond($data, $statusCode = 200, $headers = [])
    {
        return response($data, $statusCode, $headers);
    }

    protected function respondWithToken($token)
    {
        return $this->respond([
            'data' => [
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::factory()->getTTL() * 60
            ],
        ], 200);
    }

    protected function respondSuccess()
    {
        return $this->respond(null, 204);
    }

    protected function respondUnauthorized()
    {
        return $this->respond(['data' => ['error' => 'Unauthorized']], 401);
    }
}
