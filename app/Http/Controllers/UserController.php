<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function index() {
        $users = User::paginate();
        return UserResource::collection($users);
    }
    public function show(string $id) {
        if (!$user = User::find($id)) {
            return response()->json([
                "message"=>"Not Found"
            ],Response::HTTP_NOT_FOUND);
        }
        // $user = User::findOrFail($id);
        return new UserResource($user);
    }
    public function store(StoreUserRequest $request) {
        /* dd([bcrypt("123a"),Hash::make("123a")]); */
        $request->validated();
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        return new UserResource($user);
    }
    public function update(UpdateUserRequest $request,string $id) {
        if (!$user = User::find($id)) {
            return response()->json([
                "message"=>"Not Found"
            ],Response::HTTP_NOT_FOUND);
        }
        $request->validated();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        return new UserResource($user);
    }
    public function destroy(string $id) {
        if (!$user = User::find($id)) {
            return response()->json([
                "message"=>"Not Found"
            ],Response::HTTP_NOT_FOUND);
        }
        $user->destroy($id);
        return response()->json([
            "message"=>"success"
        ],Response::HTTP_ACCEPTED); 
    }
}
