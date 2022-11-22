<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
//        $this->middleware('permission:read-notification', ['only' => ['index', 'show']]);
    }

    public function index()
    {
        return view('dashboard.notifactions.index', [
            'title' => __('dashboard.show_all_notifications'),
            'notifications' => Notification::primary()->latest()->paginate(10)
        ]);
    }

    public function destroy($id): JsonResponse
    {
        try {
            $item = Notification::whereId($id)->first();
            $item->delete();

            return response()->json([
                'message' => trans('dashboard.notification_delete_successfully')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => trans('dashboard.failed_to_delete_row')
            ]);
        }
    }
}
