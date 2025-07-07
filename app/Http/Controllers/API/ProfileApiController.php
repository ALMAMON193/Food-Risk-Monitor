<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\ProfileResource;
use App\Traits\ApiResponse;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Exception;

class ProfileApiController extends Controller
{
    use ApiResponse;

    /**
     * Get the authenticated user's profile.
     */
    public function profileDetails(): JsonResponse
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return $this->sendError(__('Please login first'), [], 401);
            }

            return $this->sendResponse(new ProfileResource($user), __('Profile retrieved successfully.'));
        } catch (Exception $e) {
            return $this->sendError(__('Failed to fetch profile'), ['error' => $e->getMessage()]);
        }
    }

    /**
     * Update the user's name and avatar.
     */
    public function profileUpdate(ProfileUpdateRequest $request): JsonResponse
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return $this->sendError(__('Please login first'), [], 401);
            }

            // Update name
            $user->name = $request->input('name');

            // If a new avatar is uploaded
            if ($request->hasFile('avatar')) {
                // Delete old avatar
                Helper::fileDelete($user->avatar);

                // Upload new avatar
                $user->avatar = Helper::fileUpload($request->file('avatar'), 'uploads/avatars');
            }

            $user->save();

            return $this->sendResponse(new ProfileResource($user), __('Profile updated successfully.'));
        } catch (Exception $e) {
            return $this->sendError(__('Failed to update profile'), ['error' => $e->getMessage()]);
        }
    }
}
