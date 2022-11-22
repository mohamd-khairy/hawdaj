@extends('layouts.dashboard.master')

@section('page_header')
<h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.swalefs')</h5>
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item text-muted">
        <a href="javascript:;" class="text-muted">@lang('dashboard.dashboard')</a>
    </li>
    <li class="breadcrumb-item text-muted">
        <a href="javascript:;" class="text-muted">@lang('dashboard.swalefs')</a>
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
                    <h3 class="card-label">@lang('dashboard.swalefs_list')</h3>
                </div>
                <div class="card-toolbar">
                    <a href="{{route('dashboard.swalefs.create')}}" class="btn btn-primary">
                        <i class="la la-plus"></i>@lang('dashboard.new_swalef')
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table" id="dataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('dashboard.image')</th>
                            <th>@lang('dashboard.title')</th>
                            <th>@lang('dashboard.description')</th>
                            <th>@lang('dashboard.type')</th>
                            <th>@lang('dashboard.content')</th>
                            <th>@lang('dashboard.active')</th>
                            <th>@lang('dashboard.created_at')</th>
                            <th>@lang('dashboard.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($swalefs->first()))
                        @foreach($swalefs as $index => $swalef)
                        <tr id="row-{{$swalef->id}}">
                            <td>{{$index + 1}}</td>
                            <td>
                                <a style="width: 200px;" href="{{resolvePhoto($swalef->image)}}" target="_blank">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-60 flex-shrink-0">
                                            <div class="symbol-label" style="background-image: url({{resolvePhoto($swalef->image)}})">
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </td>
                            <td>{{ $swalef->title ?? '---'}}</td>
                            <td>{{ $swalef->description ? substr( $swalef->description, 0 , 50) : '---'}}</td>
                            <td>
                                <span class="badge badge-primary">
                                    {{$swalef->type}}
                                </span>

                            </td>
                            <td>
                                @if(file_exists('storage/'.$swalef->content))
                                <a style="width: 200px;" href="{{resolvePhoto($swalef->content)}}" target="_blank">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-60 flex-shrink-0">
                                            <div class="symbol-label" style="background-image: url({{resolvePhoto($swalef->content)}})">
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                @else
                                {{ $swalef->content }}
                                @endif
                            </td>
                            <td class="text-center">
                                @if($swalef->active)
                                <i class="fa fa-check-circle text-success"></i>
                                @else
                                <i class="fa fa-times-circle text-danger"></i>
                                @endif
                            </td>
                            <td class="number">{{ dateFormat($swalef->created_at) ?? '---' }}</td>
                            <td>
                                <a href="{{route('dashboard.swalefs.edit', $swalef->id)}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="{{__('dashboard.edit')}}">
                                    <i class="flaticon-edit-1 edit-icon"></i>
                                </a>
                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md delete-button" title="{{__('dashboard.delete')}}" data-toggle="modal" data-target="#delete_modal" data-url="{{ route('dashboard.swalefs.destroy',$swalef->id) }}" data-item-id="{{ $swalef->id }}">
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