<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(), [
            'name'=>'required',
            'username'=>'required|unique:users,username',
            'dob'=>'required|date',
            'password'=>'confirmed|required',
            'email'=>'required|email|unique:users,email',
            ]
        );

        if($validator->fails()) {
            $this->sendError('Validator Error', $validator->errors());
        }

        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->dob = $request->dob;
        $user->password = Hash::make($request->password);
        $user->role_id = config('roles.user');

        $this->sendSuccess($user, 'create User success');

    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $this->sendSuccess($user, 'Send user');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
