<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\SaveZadElgadelRequest;
use App\Http\Requests\Dashboard\UpdateZadElgadelRequest;
use App\Models\CategoryOfStore;
use App\Models\CategoryOfZad;
use App\Models\City;
use App\Models\Place;
use App\Models\Region;
use App\Models\Store;
use App\Models\ZadElgadel;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ZadElgadelController extends Controller
{

    public function index(Request $request)
    {
        visit(['ip' => \request()->ip(), 'page' => 'stores', 'visits' => 1]);

        $allCategories = CategoryOfZad::all();

        $all = ZadElgadel::when($request->title, function ($q) use ($request) {
            return $q->where('title', 'like', '%' . $request->title . '%');
        })->when($request->categories, function ($qq) use ($request) {
            return $qq->where('categories', $request->categories);
        });


        if (request('archive', 0)) {
            $all = $all->onlyTrashed();
        }

        $all = $all->latest()->paginate(10);


        return view('dashboard.zad_elgadels.index', [
            'title' => trans('dashboard.zad_elgadels'),
            'zad_elgadels' => $all,
            'categories' => $allCategories,
        ]);
    }



    public function activate(Request $request)
    {

        $active = $request->input('checked');
        $zad_elgadel_id = $request->input('zad_elgadelId');

        if ($zad_elgadel_id) {
            $zad_elgadel = ZadElgadel::find($zad_elgadel_id);

            if ($active == 1) {
                $zad_elgadel->update([
                    'active' => 1,
                ]);
            } else {
                $zad_elgadel->update([
                    'active' => 0,
                ]);
            }
            return response()->json([
                'status' => true,
                'message' => "activation updated",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "activation cant be updated",
            ]);
        }
    }


    public function create()
    {
        return view('dashboard.zad_elgadels.create', [
            'title' => __('dashboard.new_zad_elgadels'),
            'categories' => CategoryOfZad::all(),
            'regions' => Region::all(),
            'cities' => City::all(),
            'places' => Place::all(),
            'stores' => Store::all(),
        ]);
    }


    public function store(SaveZadElgadelRequest $request)
    {
        try {
            $data = $request->only([
                'title', 'categories', 'address_type', 'address', 'description',
                'active', 'featured', 'facebook_link', 'whatsapp', 'Instagram_link',
                'website_link', 'visited', 'lat', 'long', 'city_id', 'region_id'
            ]);

            // $data = $request->except('_token', 'link');


            if (isset($request->active)) {
                $data['active'] = 1;
            } else {
                $data['active'] = 0;
            }

            if (isset($request->featured)) {
                $data['featured'] = 1;
            } else {
                $data['featured'] = 0;
            }

            if ($request->address_type == null) {
                $data['address_type'] = 'link';
            } elseif ($request->address_type == 'link') {
                unset($request['lat'], $request['long']);
                $data['address'] = request('link');
            }

            if (request('categories')) {
                $data['categories'] = collect($request->categories)->map(fn ($i) => (int) $i);
            }

            if (request()->hasFile('image')) {
                $data['image'] = UploadService::store($request->image, 'zad_elgadels');
            }

            $res = ZadElgadel::create($data);

            DB::commit();

            if ($res) {
                return redirect(route('dashboard.zad_elgadels.edit', $res->id))->with([
                    'message' => trans('dashboard.zad_elgadels_added_successfully'),
                ]);
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
            return unKnownError($e->getMessage());
        }
    }


    public function edit(ZadElgadel $zad_elgadel)
    {
        $inside_map = isset($place['distance']) && $place['distance'] > 0 ? ZadElgadel::select('id', 'lat', 'long')->where('id', '!=', $zad_elgadel->id)->where('active', 1)->get()->map(function ($i) use ($zad_elgadel) {
            if (round(distance($zad_elgadel['lat'], $zad_elgadel['long'], $i['lat'], $i['long'], "M"), 1) <= $zad_elgadel['distance'] ?? 0) {
                return (string)$i['id'];
            }
        }) : [];

        $all_related_items = array_unique(array_merge(array_filter($inside_map ? $inside_map->toArray() : []) ?? [], $zad_elgadel->related_stores ?? []));

        return view('dashboard.zad_elgadels.edit', [
            'title' => __('dashboard.edit_zad_elgadels'),
            'categories' => CategoryOfZad::all(),
            'regions' => Region::all(),
            'cities' => City::all(),
            'places' => Place::all(),
            'stores' => Store::all(),
            'data' => $zad_elgadel,
            'all_related_items' => $all_related_items
        ]);
    }


    public function related(Request $request, $id)
    {
        try {

            $request->validate([
                'related_stores' => 'nullable',
                'distance' => 'nullable'
            ]);

            $inputs = $request->only(['related_stores', 'distance']);
            $inputs['related_stores'] = isset($inputs['related_stores']) ? $inputs['related_stores']  : null;
            $data = ZadElgadel::find($id);
            $data->update($inputs);
            return redirect()->back()->with([
                'message' => trans('dashboard.stores_updated_successfully'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }

    public function near(Request $request, $id)
    {
        try {
            $request->validate([
                'near_places' => 'nullable',
            ]);

            $inputs = $request->only(['near_places']);
            $inputs['near_places'] = isset($inputs['near_places']) ? $inputs['near_places']  : null;
            $data = ZadElgadel::find($id);
            $data->update($inputs);

            return redirect()->back()->with([
                'message' => trans('dashboard.places_updated_successfully'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }


    public function update(UpdateZadElgadelRequest $request, $id)
    {
        try {
            $data = $request->only([
                'title', 'categories', 'featured', 'related_stores', 'near_places', 'address_type', 'address', 'lat', 'long', 'description', 'image',
                'active', 'facebook_link', 'whatsapp', 'Instagram_link',
                'website_link', 'visited','city_id','region_id'
            ]);

            if ($request->address_type == null) {
                $data['address_type'] = 'link';
            } elseif ($request->address_type == 'link') {
                unset($request['lat'], $request['long']);
            }

            if (isset($request->active)) {
                $data['active'] = 1;
            } else {
                $data['active'] = 0;
            }

            if (isset($request->featured)) {
                $data['featured'] = 1;
            } else {
                $data['featured'] = 0;
            }

            if (request('categories')) {
                $data['categories'] = collect($request->categories)->map(fn ($i) => (int) $i);
            }

            if (request()->hasFile('image')) {
                $data['image'] = UploadService::store($request->image, 'stores');
            }

            $res = ZadElgadel::updateOrCreate(['id' => $id], $data);
            DB::commit();
            if ($res) {
                return redirect(route('dashboard.zad_elgadels.edit', $res->id))->with([
                    'message' => trans('dashboard.stores_updated_successfully'),
                ]);
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            return ZadElgadel::withTrashed()->find($id)->restore();
        } catch (\Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            if (request('archive')) {
                $zad_elgadel = ZadElgadel::withTrashed()->find($id);
                $zad_elgadel->forceDelete();
            } else {
                $zad_elgadel = ZadElgadel::findOrFail($id);
                $zad_elgadel->delete();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }

    public function destroy_selected(Request $request)
    {
        $arr_ids = $request->array_ids;
        if (request('archive')) {
            ZadElgadel::withTrashed()->whereIn('id', $arr_ids)->forceDelete();
        } else {
            ZadElgadel::whereIn('id', $arr_ids)->delete();
        }
        return response()->json([
            'status' => true,
            'message' => "zad elgadels deleted",
        ]);
    }
}
