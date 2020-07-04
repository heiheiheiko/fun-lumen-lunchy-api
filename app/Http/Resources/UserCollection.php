<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\User;

class UserCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function (User $user) {
            return (new UserResource($user));
        });
    }
}
