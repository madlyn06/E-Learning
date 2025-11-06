<?php

namespace Modules\Elearning\Services;

use Modules\Elearning\Models\User;
use Modules\Elearning\Models\TrackingLogin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthService
{
    public function register(array $data)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ]);
            $token = $user->createToken('authToken')->plainTextToken;
            event(new \Modules\Elearning\Events\UserRegistered($user, $token));
            DB::commit();
            return [
                'user' => $user,
                'token' => $token,
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function login(array $data, Request $request)
    {
        $email = $data['email'];
        $password = $data['password'];
        $user = User::where('email', $email)->first();
        if ($user && !$user->hasVerifiedEmail()) {
            return ['error' => 'Email not verified', 'code' => Response::HTTP_UNAUTHORIZED];
        }
        $status = Auth::attempt([
            'email' => $email,
            'password' => $password
        ]);
        if (!$status) {
            return ['error' => 'Unauthorized', 'code' => Response::HTTP_UNAUTHORIZED];
        }
        // Device login tracking logic
        $device = $request->header('User-Agent');
        $ip = $request->ip();
        $userId = $user->id;
        $now = now();
        $deviceCount = TrackingLogin::where('user_id', $userId)->distinct('device')->count('device');
        if ($deviceCount >= 3 && !TrackingLogin::where('user_id', $userId)->where('device', $device)->exists()) {
            return ['error' => 'Login not allowed: more than 3 devices detected.', 'code' => Response::HTTP_FORBIDDEN];
        }
        TrackingLogin::updateOrCreate(
            [
                'user_id' => $userId,
                'device' => $device,
            ],
            [
                'ip' => $ip,
                'last_logged_in' => $now,
            ]
        );
        $token = $request->user()->createToken('authToken')->plainTextToken;
        event(new \Modules\Elearning\Events\UserLoggedIn($user));
        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function forgotPassword(array $data)
    {
        $email = $data['email'];
        $user = User::where('email', $email)->first();
        if (!$user) {
            return ['error' => 'User not found.', 'code' => Response::HTTP_NOT_FOUND];
        }
        $token = app('auth.password.broker')->createToken($user);

        event(new \Modules\Elearning\Events\UserForgotPassword($user, $token));

        return ['message' => 'Password reset link sent to your email.'];
    }

    public function resetPassword(array $data)
    {
        $email = $data['email'];
        $token = $data['token'];
        $password = $data['password'];
        $user = User::where('email', $email)->first();
        if (!$user) {
            return ['error' => 'User not found.', 'code' => Response::HTTP_NOT_FOUND];
        }
        $broker = app('auth.password.broker');
        $reset = $broker->reset(
            [
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $data['password_confirmation'] ?? $password,
                'token' => $token,
            ],
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );
        if ($reset == Password::PASSWORD_RESET) {
            return ['message' => 'Password has been reset successfully.'];
        }
        return ['error' => 'Invalid token or unable to reset password.', 'code' => Response::HTTP_UNPROCESSABLE_ENTITY];
    }
}
