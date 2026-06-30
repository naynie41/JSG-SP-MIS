<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Access\Enums\UserStatus;
use App\Domain\Access\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Issue a bearer token for valid credentials (PRD FR-UAM-04).
     *
     * All failure modes (unknown email, wrong password, inactive account)
     * return the same generic error so account existence and status are never
     * revealed (SECURITY.md §2).
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $user = User::where('email', $credentials['email'])->first();

        if (! $this->credentialsAreValid($user, $credentials['password'])) {
            return ApiResponse::error('INVALID_CREDENTIALS', 'Invalid credentials.', [], 401);
        }

        $user->forceFill(['last_login_at' => now()])->save();

        $token = $user->createToken('api')->plainTextToken;

        return ApiResponse::success([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => (new UserResource($user->load('role.permissions', 'mda')))->resolve($request),
        ]);
    }

    /**
     * Return the authenticated user with role, permissions and MDA.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('role.permissions', 'mda');

        return ApiResponse::success((new UserResource($user))->resolve($request));
    }

    /**
     * Revoke the token used for the current request (PRD logout).
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return ApiResponse::success(['message' => 'Logged out.']);
    }

    /**
     * Change the authenticated user's password. Saving a changed password
     * invalidates every existing token (handled on the User model), so the
     * client must sign in again.
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = $request->user();
        $user->password = $request->validated()['password'];
        $user->save();

        return ApiResponse::success([
            'message' => 'Password updated. Please sign in again.',
        ]);
    }

    /**
     * Constant-ish time credential check that also guards account status.
     * Runs a hash comparison even when the user is missing to avoid leaking
     * account existence via response timing.
     */
    private function credentialsAreValid(?User $user, string $password): bool
    {
        if ($user === null) {
            // Spend comparable time hashing so a missing account is not
            // distinguishable from a wrong password by response timing.
            Hash::make($password);

            return false;
        }

        return Hash::check($password, $user->password)
            && $user->status === UserStatus::Active;
    }
}
