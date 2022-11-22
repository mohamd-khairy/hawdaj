<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Settings\RegionRequest;
use App\Models\Region;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class RegionController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-region', ['only' => ['index']]);
        $this->middleware('permission:create-region', ['only' => ['create', 'store']]);
        $this->middleware('permission:update-region', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-region', ['only' => ['destroy']]);
    }

    public function index(): View
    {
        return view('dashboard.settings.regions.index', [
            'title'   => trans('dashboard.regions'),
            'regions' => Region::all(),
        ]);
    }

    public function create(): View
    {
        return view('dashboard.settings.regions.create', [
            'title' => __('dashboard.create_region'),
        ]);
    }

    public function store(RegionRequest $request)
    {
        try {
            $data = $request->validated();

            Region::create($data);

            return redirect(route('dashboard.regions.index'))->with([
                'message' => trans('dashboard.region_added_successfully'),
            ]);

        } catch (Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    public function edit(Region $region)
    {
        return view('dashboard.settings.regions.edit', [
            'title'  => __('dashboard.edit_region'),
            'region' => $region,

        ]);
    }

    public function update(RegionRequest $request, Region $region)
    {
        $data = $request->validated();

        $region->update($data);

        return redirect(route('dashboard.regions.index'))->with([
            'message' => trans('dashboard.region_updated_successfully'),
        ]);
    }

    public function destroy(Region $region): JsonResponse
    {
        $region->delete();

        return response()->json([
            'message' => trans('dashboard.region_delete_successfully'),
        ]);
    }
}
