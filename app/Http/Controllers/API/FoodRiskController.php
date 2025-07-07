<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CalculateFoodRiskRequest;
use App\Http\Resources\FoodCategoryResource;
use App\Http\Resources\FoodRiskCalculateResource;
use App\Http\Resources\FoodRiskResource;
use App\Models\Food;
use App\Models\FoodRiskHistory;
use App\Traits\ApiResponse;
use App\Helpers\NumberHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Exception;

class FoodRiskController extends Controller
{
    use ApiResponse;

    /**
     * Display top food risk categories and samples.
     */
    public function listFoodRisk(): JsonResponse
    {
        try {
            // Fetch all unique food categories
            $categories = Food::select('food_category')->distinct()->get();

            // Get top 3 high, moderate, and low risk foods
            $highRisk = Food::whereRaw('LOWER(TRIM(fodmap_rating)) = ?', ['high'])
                ->orderByDesc('bloating_risk_standard')->take(3)->get();

            $moderateRisk = Food::whereRaw('LOWER(TRIM(fodmap_rating)) = ?', ['moderate'])
                ->orderByDesc('bloating_risk_standard')->take(3)->get();

            $lowRisk = Food::whereRaw('LOWER(TRIM(fodmap_rating)) = ?', ['low'])
                ->orderByDesc('bloating_risk_standard')->take(3)->get();

            return $this->sendResponse([
                'categories'   => FoodCategoryResource::collection($categories),
                'highRisk'     => FoodRiskResource::collection($highRisk),
                'moderateRisk' => FoodRiskResource::collection($moderateRisk),
                'lowRisk'      => FoodRiskResource::collection($lowRisk),
            ], __('Food risk listed successfully.'));
        } catch (Exception $e) {
            return $this->sendError(__('Failed to retrieve food risks'), ['error' => $e->getMessage()]);
        }
    }

    /**
     * Calculate bloating risk score based on food and quantity.
     */
    public function calculateFoodRisk(CalculateFoodRiskRequest $request): JsonResponse
    {
        try {
            // Step 1: Extract request inputs
            $foodName = $request->input('food_name');
            $userQtyFloat = NumberHelper::toFloat($request->input('serving_quantity'));
            $usMeasurement = $request->input('us_measurement');
            $metricMeasurement = $request->input('metric_measurement');

            // Step 2: Find matching foods
            $candidateFoods = Food::whereRaw('LOWER(food_name) = ?', [strtolower($foodName)])
                ->where('us_measurement', 'like', '%' . $usMeasurement . '%')
                ->where('metric_measurement', 'like', '%' . $metricMeasurement . '%')
                ->get();

            if ($candidateFoods->isEmpty()) {
                return $this->sendError(__('Food not found.'), [], 404);
            }

            // Step 3: Match the exact serving quantity
            $matchedFood = $candidateFoods->firstWhere(function ($food) use ($userQtyFloat) {
                return abs(NumberHelper::toFloat($food->serving_quantity) - $userQtyFloat) < 0.001;
            });

            if (!$matchedFood) {
                return $this->sendError(__('Serving quantity not matched for this food.'), [], 404);
            }

            // Step 4: Calculate risk score
            $dbQty = NumberHelper::toFloat($matchedFood->serving_quantity);
            $baseRisk = (float) $matchedFood->bloating_risk_standard;
            $adjustedRisk = $baseRisk * $userQtyFloat * $dbQty;
            $finalRisk = min($adjustedRisk, 10.0); // Cap risk at 10

            $riskLabel = $finalRisk >= 7
                ? 'High Risk'
                : ($finalRisk >= 4 ? 'Moderate Risk' : 'Low Risk');

            $user = Auth::user();

            // Step 5: Save or update risk history
            $history = FoodRiskHistory::firstOrNew([
                'user_id'   => $user?->id,
                'food_name' => $matchedFood->food_name,
            ]);

            $history->serving_quantity   = $dbQty;
            $history->us_measurement     = $matchedFood->us_measurement;
            $history->metric_measurement = $matchedFood->metric_measurement;
            $history->meal_type          = $request->input('meal_type');
            $history->risk_score         = round($finalRisk, 2);
            $history->risk_label         = $riskLabel;

            if ($history->isDirty()) {
                $history->save();
            }

            // Step 6: Suggest low-risk alternatives
            $suggestions = Food::where('fodmap_rating', 'low')
                ->where('bloating_risk_standard', '<', 4)
                ->where('id', '!=', $matchedFood->id)
                ->orderBy('bloating_risk_standard', 'asc')
                ->take(3)
                ->get(['food_name', 'bloating_risk_standard as risk_score', 'food_category']);

            // Step 7: Build final response data object
            $data = (object)[
                'user_id'            => $user?->id,
                'name'               => $user?->name,
                'food_name'          => $matchedFood->food_name,
                'risk_score'         => $finalRisk,
                'risk_label'         => $riskLabel,
                'serving_quantity'   => $matchedFood->serving_quantity,
                'us_measurement'     => $matchedFood->us_measurement,
                'metric_measurement' => $matchedFood->metric_measurement,
                'meal_type'          => $request->input('meal_type'),
                'suggestions'        => $suggestions,
            ];

            return $this->sendResponse(
                new FoodRiskCalculateResource($data),
                __('Calculated food risk successfully.')
            );
        } catch (Exception $e) {
            return $this->sendError(__('Failed to calculate food risk'), ['error' => $e->getMessage()]);
        }
    }

    /**
     * List all distinct serving quantities.
     */
    public function quantityList(): JsonResponse
    {
        try {
            $quantity = Food::select('serving_quantity')->distinct()->get();
            return $this->sendResponse($quantity, __('Quantity list retrieved successfully.'));
        } catch (Exception $e) {
            return $this->sendError(__('Failed to fetch quantity list'), ['error' => $e->getMessage()]);
        }
    }

    /**
     * List all distinct food names.
     */
    public function foodNameList(): JsonResponse
    {
        try {
            $foodNameList = Food::select('food_name')->distinct()->get();
            return $this->sendResponse($foodNameList, __('Food name list retrieved successfully.'));
        } catch (Exception $e) {
            return $this->sendError(__('Failed to fetch food name list'), ['error' => $e->getMessage()]);
        }
    }
}
