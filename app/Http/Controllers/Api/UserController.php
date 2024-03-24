<?php

namespace App\Http\Controllers\Api;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Requests\Auth\UpdateRoleRequest;
use App\Http\Requests\Auth\UpdateUserRequest;
use App\Http\Requests\GetListRequest;
use App\Http\Requests\Auth\ChangeWPRequest;
use App\Http\Requests\Auth\ResetPasswordLinkRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Jobs\SendGreetingEmail;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Str;

class UserController extends Controller
{
    public function index()
    {
        /**
         * @var \App\Models\User
         */
        $user = Auth::user();
        $user->load(['img', 'work_plate', 'role']);

        return $this->sendSuccess($user);
    }

    public function getListAccount(GetListRequest $request)
    {
        $pageSize = config('paginate.wp-list');
        $page = $request->page ?? 1;
        $columns = ['id', 'name', 'email', 'role_id', 'wp_id'];
        $relations = ['role', 'work_plate'];

        $users = User::get($columns)->paginate($pageSize, $page, $relations);

        return $this->sendSuccess($users, 'success');
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
        $user->role_id = RoleEnum::User;

        if (isset($request->image)) {
            $user->img_id = storeImage('users', $request->file('image'));
        }

        $user->save();

        $job = new SendGreetingEmail($user);
        dispatch($job);

        return $this->sendSuccess($user, 'create User success');
    }

    public function login(LoginUserRequest $request)
    {
        $user = User::where('email', '=', $request->username)
            ->orWhere('username', '=', $request->username)->first();

        if (!Hash::check($request->password, $user->password)) {
            return $this->sendError('auth error', []);
        }

        Auth::loginUsingId($user->id);

        $user->load('role', 'work_plate', 'img');
        $res['user'] = $user;
        $res['token'] = $user->createToken("loginToken")->plainTextToken;

        return $this->sendSuccess($res, 'User login successfully.');
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
        $user->load('role', 'work-plate');

        return $this->sendSuccess($user, 'Send user');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->name =  $request->name ?? $user->name;
        $user->address =  $request->address ?? $user->address;
        $user->dob = $request->dob ?? $user->dob;
        $user->phone =  $request->phone ?? $user->phone;

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

    public function ChangeWP(ChangeWPRequest $request, User $user)
    {
        $user->wp_id = $request->wp_id;
        $user->save();

        return $this->sendSuccess($user, 'change WP success');
    }

    public function resetPasswordLink(ResetPasswordLinkRequest $request)
    {
        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return $this->sendSuccess([], 'send reset link success');
        } else {
            return $this->sendError('send reset link fail', []);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return $this->sendSuccess([], 'reset password success');
        } else {
            return $this->sendError('cant reset password', 'email invalid');
        }
    }
}
