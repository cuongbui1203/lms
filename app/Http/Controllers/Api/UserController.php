<?php

namespace App\Http\Controllers\Api;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ChangeWPRequest;
use App\Http\Requests\Auth\CreateEmployeeRequest;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Requests\Auth\ResetPasswordLinkRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\UpdateRoleRequest;
use App\Http\Requests\Auth\UpdateUserRequest;
use App\Http\Requests\GetListRequest;
use App\Jobs\SendGreetingEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Str;
use Symfony\Component\HttpFoundation\Cookie;

class UserController extends Controller
{
    /**
     * Show current users
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        /**
         * @var \App\Models\User
         */
        $user = Auth::user();
        $user->load(['img', 'work_plate', 'role']);

        return $this->sendSuccess($user);
    }

    /**
     * Get list all Account
     *
     * @param GetListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListAccount(GetListRequest $request)
    {
        $pageSize = $request->pageSize ?? config('paginate.wp-list');
        $page = $request->page ?? 1;
        $columns = ['id', 'name', 'email', 'address_id', 'role_id', 'wp_id'];
        $relations = ['role', 'work_plate', 'vehicle'];

        $users = User::get($columns)->paginate($pageSize, $page, $relations);

        return $this->sendSuccess($users, 'success');
    }

    /**
     * Register new account
     *
     * @param RegisterUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RegisterUserRequest $request)
    {
        $user = new User();
        $user->unguard();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->role_id = RoleEnum::USER;

        if (isset($request->image)) {
            $user->img_id = storeImage('users', $request->file('image'));
        }

        $user->save();

        $job = new SendGreetingEmail($user);
        dispatch($job);
        $user->unguard(false);

        return $this->sendSuccess($user, 'create User success');
    }

    /**
     * Add the CSRF token to the response cookies.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function addCookieToResponse($request, $response)
    {
        $config = config('session');

        if ($response instanceof Responsable) {
            $response = $response->toResponse($request);
        }

        $response->headers->setCookie($this->newCookie($request, $config));

        return $response;
    }

    /**
     * Create a new "XSRF-TOKEN" cookie that contains the CSRF token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $config
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    private function newCookie($request, $config)
    {
        return new Cookie(
            'XSRF-TOKEN',
            $request->session()->token(),
            Carbon::now()->addRealSeconds(60 * $config['lifetime'])->getTimestamp(),
            $config['path'],
            $config['domain'],
            $config['secure'],
            false,
            false,
            $config['same_site'] ?? null,
            $config['partitioned'] ?? false
        );
    }

    /**
     * handle Login action
     *
     * @param LoginUserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginUserRequest $request)
    {
        $user = User::where('email', '=', $request->username)
            ->orWhere('username', '=', $request->username)->first();

        if (!Hash::check($request->password, $user->password)) {
            return $this->sendError('auth error', []);
        }

        Auth::loginUsingId($user->id);

        $user->load('role', 'work_plate', 'img');
        $res = [];
        $res['user'] = $user;
        $res['token'] = $user->createToken('loginToken')->plainTextToken;
        // $res['csrf_token'] = $request->session()->token();
        $res['csrf_token'] = csrf_token();

        return $this->addCookieToResponse($request, $this->sendSuccess($res, 'User login successfully.'));
    }

    /**
     * Handle Logout action
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->sendSuccess([], 'logout success');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('role', 'work_plate', 'img', 'vehicle');

        return $this->sendSuccess($user, 'Send user');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $handler = auth()->user();
        $user->name = $request->name ?? $user->name;
        $user->address_id = $request->address_id ?? $user->address_id;
        $user->dob = $request->dob ?? $user->dob;
        $user->phone = $request->phone ?? $user->phone;
        if ($handler->role_id === RoleEnum::ADMIN) {
            $user->role_id = $request->role_id ?? $user->role_id;
            if ($request->role_id === RoleEnum::MANAGER) {
                User::where('role_id', '=', RoleEnum::MANAGER)
                    ->where('wp_id', '=', $user->wp_id)
                    ->update(['role_id' => RoleEnum::EMPLOYEE]);
            }
        }

        if ($request->hasFile('image')) {
            try {
                deleteImage($user->img_id);
            } catch (ModelNotFoundException $e) {} // phpcs:ignore

            $user->img_id = storeImage('users', $request->file('image'));
        }

        $user->load('role', 'work_plate', 'img', 'vehicle');
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
        /** @var User $handler */
        $handler = auth()->user();
        if ($handler->role_id === RoleEnum::MANAGER && $handler->wp_id !== $user->wp_id) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied',
            ], 403);
        }

        try {
            deleteImage($user->img_id);
        } catch (ModelNotFoundException $e) {} //phpcs:ignore

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
        }

        return $this->sendError('send reset link fail', []);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return $this->sendSuccess([], 'reset password success');
        }

        return $this->sendError('cant reset password', 'email invalid');
    }

    public function createEmployee(CreateEmployeeRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();
        $newUser = new User($request->only([
            'username',
            'email',
            'phone',
            'dob',
            'name',
        ]));
        $newUser->address_id = $request->address_id;
        $newUser->role_id = RoleEnum::EMPLOYEE;
        $newUser->wp_id = $user->wp_id;
        if ($request->hasFile('image')) {
            $newUser->img_id = storeImage('/user', $request->file('image'));
        }

        if ($user->role_id === RoleEnum::ADMIN) {
            $newUser->role_id = $request->role_id ?? $newUser->role_id;
            $newUser->wp_id = $request->wp_id ?? $newUser->wp_id;
            if ($request->role_id === RoleEnum::MANAGER) {
                User::where('role_id', '=', RoleEnum::MANAGER)
                    ->where('wp_id', '=', $user->wp_id)
                    ->update(['role_id' => RoleEnum::EMPLOYEE]);
            }
        }

        $newUser->unguard();
        $newUser->password = 'password';
        $newUser->save();
        $newUser->unguard(false);
        $newUser->load('role', 'work_plate', 'img');

        return $this->sendSuccess($newUser, 'create employee success');
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return $this->sendError('old password invalid', []);
        }

        $user->unguard();
        $user->password = $request->new_password;
        $user->save();
        $user->unguard(false);

        return $this->sendSuccess($user, 'change Password success');
    }
}
