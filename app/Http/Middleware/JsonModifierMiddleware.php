<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class JsonModifierMiddleware
{
    public function handle($request, Closure $next)
    {
        if ($request->isJson())
        {
            $json_array = $this->renameKeysSnake($request->json()->all());
            $request->json()->replace($json_array);
        }

        return $next($request);
    }

    protected function renameKeysSnake($array) {
        $newArray = array();
        foreach($array as $key => $value) {
            if(is_string($key)) $key = Str::snake($key);
            if(is_array($value)) $value = $this->renameKeysSnake($value);

            $newArray[$key] = $value;
        }
        return $newArray;
    }
}
