<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Http\Validators\UserValidator as ValidatorsUserValidator;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function __construct(UserRepositoryInterface $users)
    {
        $this->middleware('auth', ['except' => ['create', 'authenticate']]);
        $this->users = $users;
    }

    public function create(Request $request)
    {
        ValidatorsUserValidator::validateCreate($request->input('user'));

        $user = $this->users->create([
            'username' => $request->input('user.username'),
            'email' => $request->input('user.email'),
            'password' => Hash::make($request->input('user.password')),
        ]);

        return new UserResource($user);
    }

    public function authenticate(Request $request)
    {
        ValidatorsUserValidator::validateAuthenticate($request->input('user'));

        $credentials = $request->all()['user'];

        if (!$token = Auth::attempt($credentials)) {
            return $this->respondUnauthorized();
        }

        return $this->respondWithToken($token);
    }

    public function index()
    {
        $users = $this->users->all();
        return new UserCollection($users);
    }

    public function show($id)
    {
        $user = $this->users->find($id);
        return new UserResource($user);
    }
}
