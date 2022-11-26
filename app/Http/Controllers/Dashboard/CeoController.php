<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\UpdateCeoRequest;
use App\Models\Ceo;
use Illuminate\Support\Facades\DB;

class CeoController extends Controller
{
    public function save(UpdateCeoRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('_token');
            Ceo::updateOrCreate(['parent_id' => $data['parent_id']], $data);
            DB::commit();
            return redirect()->back()->with([
                'message' => trans('dashboard.ceo_updated_successfully'),
            ]);
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }
}