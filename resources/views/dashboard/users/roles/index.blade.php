@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.roles')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.users')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.roles')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.overview')</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">@lang('dashboard.roles_list')</h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{url('dashboard/roles/create')}}"
                           class="btn btn-primary">
                            <i class="la la-plus"></i>@lang('dashboard.new_role')</a>
                    </div>
                </div>
                <div class="card-body">
                    <!--begin: Datatable-->
                    <table class="table table-bordered" id="dataTable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('dashboard.display_name')</th>
                            <th>@lang('dashboard.name')</th>
                            <th>@lang('dashboard.users_count')</th>
                            <th>@lang('dashboard.created_at')</th>
                            <th>@lang('dashboard.updated_at')</th>
                            <th>@lang('dashboard.action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($roles->first()))
                            @foreach($roles as $index => $role)
                                <tr id="row-{{$role->id}}">
                                    <td>{{$index + 1}}</td>
                                    <td>{{ $role->label?? '---'}}</td>
                                    <td>{{ $role->name?? '---'}}</td>
                                    <td>
                                        @if(count($role->users) > 0)
                                            <span class="badge badge-success" style="font-size: 12px">
                                                 {{$role->users()->count()}} @lang('dashboard.users')
                                            </span>
                                        @else
                                            <span class="badge badge-danger" style="font-size: 12px">
                                                @lang('dashboard.not_found_users')
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ dateFormat($role->created_at) ?? '---' }}</td>
                                    <td>{{ dateFormat($role->updated_at) ?? '---' }}</td>
                                    <td>
                                        <a href="{{route('dashboard.roles.edit', $role->id)}}"
                                           class="btn btn-sm btn-clean btn-icon btn-icon-md" title="@lang('dashboard.edit')">
                                            <i class="flaticon-edit-1 edit-icon"></i>
                                        </a>
                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md delete-button"
                                            title="@lang('dashboard.delete')" data-toggle="modal" data-target="#delete_modal"
                                            data-url="{{ route('dashboard.roles.destroy',$role->id) }}"
                                            data-item-id="{{ $role->id }}">
                                            <i class="flaticon2-trash trash-icon" ></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- begin: delete modal -->
    @include('dashboard.includes.alerts.delete-modal')
    <!-- end:: delete modal -->
@endsection

