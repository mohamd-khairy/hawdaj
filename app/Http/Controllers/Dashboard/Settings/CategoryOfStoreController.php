<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Settings\SaveCategoryOfStoreRequest;
use App\Http\Requests\Dashboard\Settings\UpdateCategoryOfStoreRequest;
use App\Models\CategoryOfStore as Category;
use App\Models\CategoryOfStore;
use App\Services\UploadService;
use Exception;
use Illuminate\Http\Request;

class CategoryOfStoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.settings.categoriesOfStore.index', [
            'title' => trans('dashboard.categories'),
            'categories' => CategoryOfStore::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.settings.categoriesOfStore.create', [
            'title' => __('dashboard.create_category'),
            'categories' => CategoryOfStore::allParents(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveCategoryOfStoreRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('icon')) {
                $data['icon'] = UploadService::store($request->icon, 'users');
            }
            CategoryOfStore::create($data);

            return redirect(route('dashboard.store-categories.index'))->with([
                'message' => trans('dashboard.category_added_successfully'),
            ]);
        } catch (Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($category)
    {
        $category = CategoryOfStore::find($category);
        return view('dashboard.settings.categoriesOfStore.edit', [
            'title' => __('dashboard.edit_category'),
            'category' => $category,
            'categories' => CategoryOfStore::allParents(),

        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryOfStoreRequest $request,  $category)
    {
        $category = CategoryOfStore::find($category);
        $data = $request->validated();

        if ($request->hasFile('icon')) {
            UploadService::delete($category->icon);
            $data['icon'] = UploadService::store($request->icon, 'categories');
        }

        $category->update($data);

        return redirect(route('dashboard.store-categories.index'))->with([
            'message' => trans('dashboard.category_updated_successfully'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CategoryOfStore::find($id)->delete();

        return response()->json([
            'message' => trans('dashboard.category_delete_successfully'),
        ]);
    }
}
