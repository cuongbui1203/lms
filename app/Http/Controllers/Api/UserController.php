<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /**
         * @var \App\Models\User
         */
        $user = Auth::user();
        // $user->load(['img','work_plate']);
        return $this->sendSuccess($user);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterUserRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->role_id = config('roles.user');
        if (isset($request->image)) {
            $user->img_id = storeImage('users', $request->file('image'));
        }

        $user->save();

        return $this->sendSuccess($user, 'create User success');
    }

    public function login(LoginUserRequest $request)
    {
        $user = User::where('email', '=', $request->username)
            ->orWhere('username', '=', $request->username)->first();
        Auth::loginUsingId($user->id);
        $success['token'] = $user->createToken("loginToken")->plainTextToken;
        return $this->sendSuccess($success, 'User login successfully.');
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->sendSuccess([], "logout success");
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $this->sendSuccess($user, 'Send user');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->name = $request->name;
        $user->address = $request->address;
        $user->dob = $request->dob;
        if (isset($request->image)) {
            deleteImage($user->img_id);
            $user->img_id = storeImage('users', $request->file('image'));
        }
        $user->save();

        return $this->sendSuccess($user, 'update user success');
    }

    public function updateRole(UpdateRoleRequest $request, User $user)
    {
        $user->role_id = $request->roleIdNew;
        $user->save();
        return $this->sendSuccess('', 'Success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->img()->delete();
        $user->delete();
        return $this->sendSuccess('', 'delete success');
    }
}
