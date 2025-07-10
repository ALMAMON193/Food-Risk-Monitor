<?php

use App\Http\Controllers\API\AnalyticsApiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\FoodRiskController;
use App\Http\Controllers\API\ProfileApiController;
use App\Http\Controllers\API\Auth\AuthApiController;
use App\Http\Controllers\API\CustomFoodApiController;
use App\Http\Controllers\API\LogSymptomApiController;
use App\Http\Controllers\API\PersonalTriggerFoodController;

// ------------------------
// 🔐 Auth Routes
// ------------------------
Route::prefix('auth')->group(function () {
    Route::post('login',             [AuthApiController::class, 'loginApi']);
    Route::post('register',          [AuthApiController::class, 'registerApi']);
    Route::post('verify-email',      [AuthApiController::class, 'verifyEmailApi']);
    Route::post('forgot-password',   [AuthApiController::class, 'forgotPasswordApi']);
    Route::post('reset-password',    [AuthApiController::class, 'resetPasswordApi']);
    Route::post('resend-otp',        [AuthApiController::class, 'resendOtpApi']);
    Route::post('verify-otp',        [AuthApiController::class, 'verifyOtpApi']);

    // Logout (authenticated only)
    Route::middleware('auth:sanctum')->post('logout', [AuthApiController::class, 'logoutApi']);
});


// ------------------------
// 🥗 Food Risk Analysis
// ------------------------
Route::middleware('auth:sanctum')->prefix('foods')->group(function () {
    Route::get('/list',             [FoodRiskController::class, 'listFoodRisk']);        // List categorized food risks
    Route::post('/calculate-risk', [FoodRiskController::class, 'calculateFoodRisk']);    // Calculate risk by food & serving
});

// Public routes (optional auth)
Route::get('foods/quantity',  [FoodRiskController::class, 'quantityList']);     // Get distinct quantities
Route::get('foods/food-name', [FoodRiskController::class, 'foodNameList']);     // Get distinct food names
Route::get('foods', [FoodRiskController::class, 'allFoods']);


// ------------------------
// 🍽️ Custom Food (User-created foods)
// ------------------------
Route::middleware('auth:sanctum')->prefix('custom')->group(function () {
    Route::post('/food-store', [CustomFoodApiController::class, 'foodStore']);
});


// ------------------------
// 📋 Log Symptoms
// ------------------------
Route::middleware('auth:sanctum')->prefix('log-symptom')->group(function () {
    Route::post('/store', [LogSymptomApiController::class, 'logSymptom']);
});


// ------------------------
// ⚠️ Personal Trigger Foods
// ------------------------
Route::middleware('auth:sanctum')->prefix('personal-trigger-food')->group(function () {
    Route::get('/',            [PersonalTriggerFoodController::class, 'index']);            // List trigger foods
    Route::post('/add',        [PersonalTriggerFoodController::class, 'addFoodTrigger']);   // Add new trigger food
    Route::get('/view/{id}',   [PersonalTriggerFoodController::class, 'viewFoodTrigger']);  // View specific food
    Route::delete('/delete/{id}', [PersonalTriggerFoodController::class, 'deleteFoodTrigger']); // Delete food
});


// ------------------------
//  Personal profile
// ------------------------
Route::middleware('auth:sanctum')->prefix('profile')->group(function () {
    Route::get('/', [ProfileApiController::class, 'profileDetails']);
    Route::post('/update', [ProfileApiController::class, 'profileUpdate']);
});

// ------------------------
// Analytics route
// ------------------------
Route::middleware('auth:sanctum')->get('/analytics', [AnalyticsApiController::class, 'index']);

