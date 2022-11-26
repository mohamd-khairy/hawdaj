<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\SaveStoreRequest;
use App\Http\Requests\Dashboard\UpdateStoreRequest;
use App\Models\Category;
use App\Models\CategoryOfStore;
use App\Models\Place;
use App\Models\Store;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{

    public function index(Request $request)
    {
        visit(['ip' => \request()->ip(), 'page' => 'stores', 'visits' => 1]);

        $allCategories = CategoryOfStore::all();

        $all = Store::when($request->title, function ($q) use ($request) {
            return $q->where('title', 'like', '%' . $request->title . '%');
        })->when($request->categories, function ($qq) use ($request) {
            return $qq->where('categories', $request->categories);
        });


        if (request('archive', 0)) {
            $all = $all->onlyTrashed();
        }

        $all = $all->latest()->paginate(10);


        return view('dashboard.stores.index', [
            'title' => trans('dashboard.stores'),
            'stores' => $all,
            'categories' => $allCategories,
        ]);
    }



    public function activate(Request $request)
    {

        $active = $request->input('checked');
        $store_id = $request->input('storeId');

        if ($store_id) {
            $store = Store::find($store_id);

            if ($active == 1) {
                $store->update([
                    'active' => 1,
                ]);
            } else {
                $store->update([
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
        return view('dashboard.stores.create', [
            'title' => __('dashboard.create_store'),
            'categories' => CategoryOfStore::all(),
            'places' => Place::all(),
            'stores' => Store::all(),

        ]);
    }


    public function store(SaveStoreRequest $request)
    {
        try {
            $data = $request->only([
                'title', 'categories', 'address_type', 'address', 'description',
                'active', 'featured', 'facebook_link', 'whatsapp', 'Instagram_link',
                'website_link', 'visited', 'lat', 'long'
            ]);


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

            // if ($request->address_type == null) {
            //     $data['address_type'] = 'link';
            // } elseif ($request->address_type == 'link') {
            //     unset($request['lat'], $request['long']);
            // }

            if ($request->address_type == 'map') {
                $data['address_type'] = 'map';
                $data['address'] = request('address');
                $data['lat'] = request('lat');
                $data['long'] = request('long');
            } elseif ($request->address_type == 'link') {
                $data['address_type'] = 'link';
                $data['address'] = request('address');
                $data['lat'] = null;
                $data['long'] = null;
            } else {
                $data['address'] = request('address');
                $data['lat'] = request('lat');
                $data['long'] = request('long');
            }


            if (request('categories')) {
                $data['categories'] = collect($request->categories)->map(fn ($i) => (int) $i);
            }

            if (request()->hasFile('image')) {
                $data['image'] = UploadService::store($request->image, 'stores');
            }

            $res = Store::create($data);

            DB::commit();

            if ($res) {
                return redirect(route('dashboard.stores.edit', $res->id))->with([
                    'message' => trans('dashboard.stores_added_successfully'),
                ]);
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }


    public function edit(Store $store)
    {
        $inside_map = isset($place['distance']) && $place['distance'] > 0 ? Store::select('id', 'lat', 'long')->where('id', '!=', $store->id)->where('active', 1)->get()->map(function ($i) use ($store) {
            if (round(distance($store['lat'], $store['long'], $i['lat'], $i['long'], "M"), 1) <= $store['distance'] ?? 0) {
                return (string)$i['id'];
            }
        }) : [];

        $all_related_items = array_unique(array_merge(array_filter($inside_map ? $inside_map->toArray() : []) ?? [], $store->related_stores ?? []));

        return view('dashboard.stores.edit', [
            'title' => __('dashboard.edit_store'),
            'categories' => CategoryOfStore::all(),
            'places' => Place::all(),
            'stores' => Store::all(),
            'data' => $store,
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
            $data = Store::find($id);
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
            $data = Store::find($id);
            $data->update($inputs);

            return redirect()->back()->with([
                'message' => trans('dashboard.places_updated_successfully'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }


    public function update(UpdateStoreRequest $request, $id)
    {
        try {
             $data = $request->only([
                'title', 'categories', 'featured', 'related_stores', 'near_places', 'address_type', 'address', 'lat', 'long', 'description', 'image',
                'active', 'facebook_link', 'whatsapp', 'Instagram_link',
                'website_link', 'visited',
            ]);


            
            // if ($request->address_type == null) {
            //     $data['address_type'] = 'link';
            // } elseif ($request->address_type == 'link') {
            //     unset($request['lat'], $request['long']);
            // }

            if ($request->address_type == 'map') {
                $data['address_type'] = 'map';
                $data['address'] = request('address');
                $data['lat'] = request('lat');
                $data['long'] = request('long');
            } elseif ($request->address_type == 'link') {
                $data['address_type'] = 'link';
                $data['address'] = request('address');
                $data['lat'] = null;
                $data['long'] = null;
            } else {
                $data['address'] = request('address');
                $data['lat'] = request('lat');
                $data['long'] = request('long');
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

            
            $res = Store::updateOrCreate(['id' => $id], $data);
            DB::commit();
            if ($res) {
                return redirect(route('dashboard.stores.edit', $res->id))->with([
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
            return Store::withTrashed()->find($id)->restore();
        } catch (\Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            if (request('archive')) {
                $store = Store::withTrashed()->find($id);
                $store->forceDelete();
            } else {
                $store = Store::findOrFail($id);
                $store->delete();
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
            Store::withTrashed()->whereIn('id', $arr_ids)->forceDelete();
        } else {
            Store::whereIn('id', $arr_ids)->delete();
        }
        return response()->json([
            'status' => true,
            'message' => "stores deleted",
        ]);
    }
}
