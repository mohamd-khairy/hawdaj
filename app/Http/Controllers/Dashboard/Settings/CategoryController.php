<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Settings\CategoryRequest;
use App\Models\Category;
use App\Services\UploadService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class CategoryController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:read-category', ['only' => ['index']]);
        $this->middleware('permission:create-category', ['only' => ['create', 'store']]);
        $this->middleware('permission:update-category', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-category', ['only' => ['destroy']]);
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('dashboard.settings.categories.index', [
            'title'      => trans('dashboard.categories'),
            'categories' => Category::all(),
        ]);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('dashboard.settings.categories.create', [
            'title'      => __('dashboard.create_category'),
            'categories' => Category::allParents(),
        ]);
    }

    /**
     * @param CategoryRequest $request
     * @return Application|JsonResponse|RedirectResponse|Redirector
     */
    public function store(CategoryRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('icon')) {
                $data['icon'] = UploadService::store($request->icon, 'users');
            }
            Category::create($data);

            return redirect(route('dashboard.categories.index'))->with([
                'message' => trans('dashboard.category_added_successfully'),
            ]);

        } catch (Exception $e) {
            return unKnownError($e->getMessage());
        }
    }

    /**
     * @param Category $category
     * @return Application|Factory|View
     */
    public function edit(Category $category)
    {
        return view('dashboard.settings.categories.edit', [
            'title'      => __('dashboard.edit_category'),
            'category'   => $category,
            'categories' => Category::allParents(),

        ]);
    }

    /**
     * @param CategoryRequest $request
     * @param Category $category
     * @return Application|RedirectResponse|Redirector
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        if ($request->hasFile('icon')) {
            UploadService::delete($category->icon);
            $data['icon'] = UploadService::store($request->icon, 'categories');
        }

        $category->update($data);

        return redirect(route('dashboard.categories.index'))->with([
            'message' => trans('dashboard.category_updated_successfully'),
        ]);
    }

    /**
     * @param Category $category
     * @return JsonResponse
     */
    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json([
            'message' => trans('dashboard.category_delete_successfully'),
        ]);
    }
}
