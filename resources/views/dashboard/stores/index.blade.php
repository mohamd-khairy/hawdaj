@extends('layouts.dashboard.master')

@section('page_header')
<h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.stores')</h5>
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item text-muted">
        <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
    </li>
    <li class="breadcrumb-item text-muted">
        <a href="javascript:;" class="text-muted">{{$title}}</a>
    </li>
</ul>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('.insertion').select2({
            tags: true
        });
    });

    //disable search in data table and paging
    if ($.fn.dataTable.isDataTable('.dataTable2')) {
        table = $('.dataTable2').DataTable();
    } else {
        table = $('.dataTable2').DataTable({
            paging: false,
            searching: false,
        });
    }
    table = $('.dataTable2').DataTable({
        retrieve: true,
        paging: false,
        'searching': false
    });



    $("#checkAll").click(function() {
        if ($('#checkAll:checkbox:checked').length > 0) {
            $('.delete_selected_modal').show();
        } else {
            $('.delete_selected_modal').hide();
        }
        $('.checkboxD:checkbox').not(this).prop('checked', this.checked);
    });

    $(".checkboxD:checkbox").click(function() {
        if ($('.checkboxD:checkbox:checked').length > 0) {
            $('.delete_selected_modal').show();

        } else {
            $('.delete_selected_modal').hide();
        }
    });

    $("#delete_selected").on("click", function(e) {
        e.preventDefault();

        var listArray = [];


        $("input:checkbox[name=ids]:checked").each(function(i) {
            // var item_id = $(this).data('item-id') ;
            listArray.push($(this).val());
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ route('dashboard.stores.destroy_selected') }}",
            type: 'delete',
            data: {
                'array_ids': listArray,
            },
            success: function(data) {
                $("#delete_selected_modal").modal("toggle");
                toastr.success(data.message);
                $.each(listArray, function(key, val) {
                    $("#row-" + val).remove();
                });
                $('.delete_selected_modal').hide();
            },

            error: function(data) {
                toastr.error(data.message);

            },
        });

    });
</script>

<script>
    $('.activate').change(function() {
        var checked;
        var place_id = $(this).data('item-id');

        if ($(this).is(':checked')) {
            checked = 1;
        } else {
            checked = 0;
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{Route('dashboard.stores.active')}}",
            method: 'POST',
            data: {
                'checked': checked,
                'storeId': place_id,
            },
            success: function(data) {
                if (data.status) {
                    toastr.success(data.message);
                }
            },
        });
    });
</script>

@endpush

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">{{__('dashboard.stores_list')}}</h3>
                </div>
                <div class="card-toolbar">

                    <a href="#" class="btn btn-danger ml-2 mr-2 delete_selected_modal " style="color: white !important; display:none" data-toggle="modal" data-target="#delete_selected_modal">
                        <i class="flaticon2-trash trash-icon" style="color: white !important;"></i>@lang('dashboard.delete_selected')</a>

                    @if(!request('archive') && \App\Models\Store::onlyTrashed()->first())
                    <a href="{{route('dashboard.stores.index' , ['archive' => 1])}}" class="btn btn-warning m-2">
                        <i class="la la-trash"></i>@lang('dashboard.archive')</a>
                    @endif

                    @if(!empty($stores->first()) && !request('archive'))

                    <a href="{{route('dashboard.stores.create')}}" class="btn btn-primary"><i class="la la-plus"></i>@lang('dashboard.new_store')</a>
                    @else

                    <a href="{{route('dashboard.stores.index')}}" class="btn btn-primary m-2">@lang('dashboard.stores')</a>
                    @endif

                </div>
            </div>
            <div class="card-header flex-wrap border-0 pt-6 pb-0">

                <form action="{{ route('dashboard.stores.index') }}" method="get" style="width: -webkit-fill-available;">

                    <div class="row">

                        <div class="col-md-3 mb-3">
                            <input name="title" class="form-control" placeholder="@lang('dashboard.search')" value="{{ request()->title }}">
                        </div>


                        <div class="col-md-3 mb-3 input-group ">

                            <select name="category_id" class="form-control ">

                                <option value="">@lang('dashboard.categories')</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ request()->categories == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('dashboard.search')</button>

                        </div>

                    </div>
                </form><!-- end of form -->

            </div>
            @if(!empty($stores->first()))
            <div class="card-body">
                <!--begin: Datatable-->
                <table class="table table-bordered dataTable2" id="">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="checkAll" class="checkbox check-delete "></th>
                            <th>#</th>
                            <th>@lang('dashboard.title')</th>
                            <th>@lang('dashboard.categories')</th>
                            <th>@lang('dashboard.active')</th>
                            <th>@lang('dashboard.created_at')</th>
                            <th>@lang('dashboard.updated_at')</th>
                            <th>@lang('dashboard.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($stores->first()))
                        @foreach($stores as $index => $store)
                        <tr id="row-{{$store->id}}">
                            <td><input type="checkbox" name="ids" value="{{$store->id}}" class="checkbox checkboxD check-delete "></td>
                            <td>{{$index + 1}}</td>
                            <td>{{ $store->title ?? '---'}}</td>
                            <td>
                                @if(isset($store->categories))
                                @if(count($store->categories) > 0)
                                @foreach($store->categories as $category)
                                <span class="badge badge-pill badge-primary">{{ \App\Models\CategoryOfStore::where('id',(int)$category)->first()->name ?? '---' }}</span>
                                @endforeach
                                @endif
                                @endif
                            </td>

                            <td class="text-center">
                                <span class="switch switch-outline switch-icon switch-success">
                                    <label>
                                        <input id="" class="activate" data-item-id="{{$store->id}}" type="checkbox" @if($store->active) checked @endif name="active"
                                        value=""/>
                                        <span></span>
                                    </label>
                                </span>
                            </td>


                            <td>{{ dateFormat($store->created_at) ?? '---' }}</td>
                            <td>{{ dateFormat($store->updated_at) ?? '---' }}</td>
                            <td>

                                @if(request('archive',0))
                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md restore-button" title="{{__('dashboard.restore')}}" data-toggle="modal" data-target="#restore_modal" data-url="{{ route('dashboard.stores.restore',$store->id) }}" data-item-id="{{ $store->id }}">
                                    <i class="flaticon2-refresh refresh-icon"></i>
                                </a>
                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md delete-button" title="{{__('dashboard.force_delete')}}" data-toggle="modal" data-target="#delete_modal" data-url="{{ route('dashboard.stores.destroy',[$store->id , 'archive' => request('archive')]) }}" data-item-id="{{ $store->id }}">
                                    <i class="flaticon2-trash trash-icon"></i>
                                </a>
                                @else

                                <a href="{{route('dashboard.stores.edit', $store->id)}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="{{__('dashboard.edit')}}">
                                    <i class="flaticon-edit-1 edit-icon"></i>
                                </a>
                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md delete-button" title="{{__('dashboard.delete')}}" data-toggle="modal" data-target="#delete_modal" data-url="{{ route('dashboard.stores.destroy',$store->id) }}" data-item-id="{{ $store->id }}">
                                    <i class="flaticon2-trash trash-icon"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
                {!! str_replace('/?', '?', $stores->withQueryString()->render()) !!}

            </div>
            @else
            <div class="card-body">

                <div class="card-toolbar" style=" text-align: center; background-color: #f2f3f8 ;padding: 100px;  color: #101010">
                    <div style="font-size: large">
                        <i class="la la-briefcase mb-2 " style="font-size: xxx-large; "></i><br>
                        @lang('dashboard.no_data')
                    </div>

                    <a href="{{route('dashboard.stores.create')}}" class="btn btn-primary mt-2">
                        <i class="la la-plus"></i>@lang('dashboard.new_place')</a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>


<!-- begin: delete modal -->
@include('dashboard.includes.alerts.delete-modal')
@include('dashboard.includes.alerts.delete-selected-modal')
@include('dashboard.includes.alerts.restore-modal')


@endsection