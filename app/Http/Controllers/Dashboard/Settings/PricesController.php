<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StorePriceRequest;
use App\Http\Requests\Dashboard\UpdatePriceRequest;
use App\Models\Price;
use Illuminate\Support\Facades\DB;

class PricesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.settings.prices.index', [
            'title' => trans('dashboard.prices'),
            'prices' => Price::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.settings.prices.create', [
            'title' => __('dashboard.create_price'),
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePriceRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('_token');
            Price::create($data);
            DB::commit();
            return redirect(route('dashboard.prices.index'))->with([
                'message' => trans('dashboard.price_added_successfully'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
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
    public function edit(Price $price)
    {
        return view('dashboard.settings.prices.edit', [
            'title' => __('dashboard.edit_price'),
            'price' => $price,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePriceRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('_token', '_method');
            Price::UpdateOrCreate(['id' => $id], $data);
            DB::commit();
            return redirect(route('dashboard.prices.index'))->with([
                'message' => trans('dashboard.price_updated_successfully'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Price $price)
    {
        DB::beginTransaction();
        try {
            $price->delete();
            DB::commit();
            return response()->json([
                'message' => trans('dashboard.price_delete_successfully'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return unKnownError($e->getMessage());
        }
    }
}