<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomFoodRequest;
use App\Http\Resources\CustomFoodResource;
use App\Models\CustomFood;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Exception;

class CustomFoodApiController extends Controller
{
    use ApiResponse;

    /**
     * Store a new custom food item for the authenticated user.
     *
     * @param StoreCustomFoodRequest $request
     * @return JsonResponse
     */
    public function foodStore(StoreCustomFoodRequest $request): JsonResponse
    {
        try {
            // Ensure the user is authenticated
            $user = Auth::user();
            if (!$user) {
                return $this->sendError(__('Please login first'), []);
            }

            // Merge validated request with authenticated user ID
            $data = $request->validated();
            $data['user_id'] = $user->id;

            // Store the new custom food in the database
            $food = CustomFood::create($data);

            // Prepare the API response object
            $apiResponse = (object)[
                'food' => new CustomFoodResource($food), // Optionally use resource
            ];

            return $this->sendResponse($apiResponse, __('Custom food created successfully.'));
        } catch (Exception $e) {
            return $this->sendError(__('Failed to create custom food'), ['error' => $e->getMessage()]);
        }
    }
}
