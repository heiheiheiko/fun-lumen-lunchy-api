<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    use UserRequest;

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['create', 'login']]);
    }

    public function create(Request $request)
    {
        $this->validateCreate($request);

        $user = User::create([
            'name' => $request->input('user.name'),
            'email' => $request->input('user.email'),
            'password' => Hash::make($request->input('user.password')),
        ]);

        return new UserResource($user);
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        $credentials = $request->all()['user'];

        if (!$token = Auth::attempt($credentials)) {
            return $this->respondUnauthorized();
        }

        return $this->respondWithToken($token);
    }

    public function index()
    {
        $users = User::all();
        return new UserCollection($users);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return new UserResource($user);
    }
}
