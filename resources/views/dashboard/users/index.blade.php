@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.users')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.users')</a>
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
                        <h3 class="card-label">@lang('dashboard.users_list')</h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{route('dashboard.users.create', ['type' => app('request')->input('type') ])}}"
                           class="btn btn-primary">
                            <i class="la la-plus"></i>@lang('dashboard.new_user')
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table" id="dataTable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('dashboard.photo')</th>
                            <th>@lang('dashboard.name')</th>
                            <th>@lang('dashboard.email')</th>
                            <th>@lang('dashboard.role')</th>
                            <th>@lang('dashboard.department')</th>
                            <th>@lang('dashboard.created_at')</th>
                            <th>@lang('dashboard.action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($users->first()))
                            @foreach($users as $index => $user)
                                <tr id="row-{{$user->id}}">
                                    <td>{{$index + 1}}</td>
                                    <td>
                                        <a style="width: 200px;" href="{{resolvePhoto($user->photo)}}" target="_blank">
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-60 flex-shrink-0">
                                                    <div class="symbol-label"
                                                         style="background-image: url({{resolvePhoto($user->photo)}})">
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td>{{ $user->full_name ?? '---'}}</td>
                                    <td>{{ $user->email ?? '---'}}</td>
                                    <td>
                                        @if($user->roles()->first())
                                            @foreach($user->roles()->get() as $role)
                                                <span class="badge badge-primary">
                                                  {{$role->label}}
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="badge badge-danger">
                                                {{ trans('dashboard.no_role')}}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{$user->department->name=='All'?'danger':'success'}}">
                                            {{$user->department->name}}
                                        </span>
                                    </td>
                                    <td class="number">{{ dateFormat($user->created_at) ?? '---' }}</td>
                                    <td>
                                        <a href="{{route('dashboard.users.edit', $user->id)}}"
                                           class="btn btn-sm btn-clean btn-icon btn-icon-md" title="{{__('dashboard.edit')}}">
                                            <i class="flaticon-edit-1 edit-icon"></i>
                                        </a>
                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md delete-button"
                                           title="{{__('dashboard.delete')}}" data-toggle="modal" data-target="#delete_modal"
                                           data-url="{{ route('dashboard.users.destroy',$user->id) }}"
                                           data-item-id="{{ $user->id }}">
                                            <i class="flaticon2-trash trash-icon"></i>
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

