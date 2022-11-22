<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CarPermissionRequest;
use App\Models\CarRequest;
use Illuminate\Http\JsonResponse;

class CarRequestController extends Controller
{
    public function check(CarPermissionRequest $request): JsonResponse
    {
        $carRequest = CarRequest::whereHas('car', function ($q) use ($request) {
            return $q->where('plate_en', $request->plate_card)->orWhere('plate_ar', $request->plate_card);
        })->where('site_id', $request->site_id)->where('delivery_date', now()->toDateString())->first();

        if (empty($carRequest)) {
            return response()->json([
                'message' => trans('dashboard.invalid_request'),
                'status' => 'fail'
            ], 404);
        }

        return response()->json([
            'message' => trans('dashboard.car_has_success_request'),
            'status' => 'success'
        ]);
    }
}
