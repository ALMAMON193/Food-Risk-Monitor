<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLogSymptomRequest;
use App\Http\Resources\LogSymptomResource;
use App\Models\LogSymptom;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Exception;

class LogSymptomApiController extends Controller
{
    use ApiResponse;

    /**
     * Store a newly logged symptom for the authenticated user.
     *
     * @param StoreLogSymptomRequest $request
     * @return JsonResponse
     */
    public function logSymptom(StoreLogSymptomRequest $request): JsonResponse
    {
        try {
            // Ensure the user is authenticated
            $user = Auth::user();
            if (!$user) {
                return $this->sendError(__('Please login first'), []);
            }

            // Merge validated request with user_id
            $data = $request->validated();
            $data['user_id'] = $user->id;

            // Create log entry in DB
            $logSymptom = LogSymptom::create($data);

            // Format and return the API response
            $resource = new LogSymptomResource($logSymptom);
            return $this->sendResponse($resource, __('Symptom logged successfully.'));
        } catch (Exception $e) {
            // Return detailed error for debugging (hide in production)
            return $this->sendError(__('Failed to log symptom'), ['error' => $e->getMessage()]);
        }
    }
}
