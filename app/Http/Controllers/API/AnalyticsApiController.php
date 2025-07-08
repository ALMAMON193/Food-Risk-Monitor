<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnalyticsResource;
use App\Services\AnalyticsService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class AnalyticsApiController extends Controller
{
    use ApiResponse;
    public function index(Request $request)
    {
        $period = $request->get('period', 'last_7_days');
        $userId = auth()->id(); // or pass user_id if needed
//        dd($userId);

        $data = (new AnalyticsService)->generate($period, $userId);


        return response()->json([
            'success' => true,
            'data' => new AnalyticsResource($data),
            'message' => 'Analytics generated successfully.'
        ]);
    }
}
