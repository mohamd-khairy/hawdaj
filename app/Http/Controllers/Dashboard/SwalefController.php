<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Swalef;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SwalefController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $swalefs = Swalef::get();

        return view('dashboard.swalef.index', [
            'title' => trans('dashboard.swalefs'),
            'swalefs' => $swalefs
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 
        return view('dashboard.swalef.create', [
            'types' => Swalef::TYPES,
            'title' => __('dashboard.create_swalef')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data = $request->except('_token');

            \DB::beginTransaction();

            if ($request->hasFile('image')) {
                $data['image'] = UploadService::store($request->image, 'swalefs');
            }
            if ($request->hasFile('content')) {
                $data['content'] = UploadService::store($request->content, 'swalefs');
            }
            Swalef::create($data);

            \DB::commit();
            return redirect(route('dashboard.swalefs.index'))->with([
                'message' => trans('dashboard.swalef_added_successfully')
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
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
    public function edit(Swalef $swalef)
    {
        return view('dashboard.swalef.edit', [
            'title' => __('dashboard.edit_swalef'),
            'types' => Swalef::TYPES,
            'swalef' => $swalef
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param swalef $swalef
     * @return \Illuminate\Http\Response
     */
    public function update(Swalef $swalef, Request $request)
    {
        try {
            $data = $request->except('_token');


            \DB::beginTransaction();

            if ($request->hasFile('image')) {
                UploadService::delete($swalef->image);
                $data['image'] = UploadService::store($request->image, 'swalefs');
            }
            if ($request->hasFile('content')) {
                UploadService::delete($swalef->content);
                $data['content'] = UploadService::store($request->content, 'swalefs');
            }

            $swalef->update($data);
            \DB::commit();
            return redirect(route('dashboard.swalefs.index'))->with([
                'message' => trans('dashboard.swalef_added_successfully')
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param swalef $swalef
     * @return \Illuminate\Http\Response
     */
    public function destroy(Swalef $swalef)
    {
        UploadService::delete($swalef->image);
        UploadService::delete($swalef->content);
        $swalef->delete();

        return response()->json([
            'message' => trans('dashboard.swalef_delete_successfully')
        ]);
    }
}
