<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePersonalTriggerFoodRequest;
use App\Http\Resources\PersonalTriggerFoodResource;
use App\Models\PersonalTriggerFood;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Exception;

class PersonalTriggerFoodController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the personal trigger foods for the authenticated user.
     */
    public function index(): JsonResponse
    {
        try {
            $auth = Auth::user();

            if (!$auth) {
                return $this->sendError(__('Please login first'), [], 401);
            }

            // Fetch user's personal trigger foods
            $foods = PersonalTriggerFood::where('user_id', $auth->id)->latest()->get();

            // Get distinct categories
            $categories = $foods->pluck('food_category')->unique()->values();

            return $this->sendResponse([
                'categories' => $categories,
                'personal_trigger_food' => PersonalTriggerFoodResource::collection($foods),
            ], __('Foods retrieved successfully.'));
        } catch (Exception $e) {
            return $this->sendError(__('Something went wrong'), ['error' => $e->getMessage()]);
        }
    }

    /**
     * View a single personal trigger food by ID.
     */
    public function viewFoodTrigger($id): JsonResponse
    {
        try {
            // Check user authentication
            $auth = Auth::user();
            if (!$auth) {
                return $this->sendError(__('Please login first'), []);
            }

            // Retrieve food that belongs to the user
            $food = PersonalTriggerFood::where('user_id', $auth->id)->find($id);
            if (!$food) {
                return $this->sendError(__('Food not found'), []);
            }

            // Return the food resource
            $resource = new PersonalTriggerFoodResource($food);
            return $this->sendResponse($resource, __('Food retrieved successfully.'));
        } catch (Exception $e) {
            return $this->sendError(__('Something went wrong'), ['error' => $e->getMessage()]);
        }
    }

    /**
     * Add a new personal trigger food.
     */
    public function addFoodTrigger(StorePersonalTriggerFoodRequest $request): JsonResponse
    {
        try {
            // Validate and prepare data with user_id
            $data = $request->validated();
            $data['user_id'] = Auth::id();

            // Store the personal trigger food
            $food = PersonalTriggerFood::create($data);

            // Return the resource
            $resource = new PersonalTriggerFoodResource($food);
            return $this->sendResponse($resource, __('Food created successfully.'));
        } catch (Exception $e) {
            return $this->sendError(__('Failed to create food'), ['error' => $e->getMessage()]);
        }
    }

    /**
     * Delete a personal trigger food.
     */
    public function deleteFoodTrigger($id): JsonResponse
    {
        try {
            // Authenticate user
            $auth = Auth::user();
            if (!$auth) {
                return $this->sendError(__('Please login first'), []);
            }

            // Find the food for this user
            $food = PersonalTriggerFood::where('user_id', $auth->id)->find($id);
            if (!$food) {
                return $this->sendError(__('Food not found'), []);
            }

            // Delete the food
            $food->delete();

            return $this->sendResponse([], __('Food deleted successfully.'));
        } catch (Exception $e) {
            return $this->sendError(__('Failed to delete food'), ['error' => $e->getMessage()]);
        }
    }
}
