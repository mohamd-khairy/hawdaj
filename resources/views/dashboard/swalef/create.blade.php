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
        <a href="javascript:;" class="text-muted">@lang('dashboard.create_swalef')</a>
    </li>
</ul>
@endsection

@push('js')
<script>
    $(function() {
        $('#type').on('change', function() {
            let selected_option = $(this).find(':selected').data('name');

            if (selected_option == 'text') {
                $("#textarea").hide();
                $("#textarea").attr('disabled' , 'disabled');
                $(".file_input").hide();
                $(".file_input").attr('disabled' , 'disabled');
                $("#text").show();
                $("#text").removeAttr('disabled');
                $("#content_section").show();

            } else if (selected_option == 'textarea') {
                $("#text").hide();
                $("#text").attr('disabled' , 'disabled');

                $(".file_input").hide();
                $(".file_input").attr('disabled' , 'disabled');

                $("#textarea").show();
                $("#textarea").removeAttr('disabled');

                $("#content_section").show();
            } else if (selected_option == 'file') {
                $("#textarea").hide();
                $("#textarea").attr('disabled' , 'disabled');

                $("#text").hide();
                $("#text").attr('disabled' , 'disabled');

                $(".file_input").show();
                $(".file_input").removeAttr('disabled');

                $("#content_section").show();
            } else {
                $("#content_section").hide();
            }
        });

    });
</script>
@endpush

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-custom gutter-b ">
                    <div class="card-header">
                        <h3 class="card-title">@lang('dashboard.create_swalef')</h3>
                        <div class="card-toolbar">
                            <a href="{{url('dashboard/swalefs')}}" class="btn btn-primary ">
                                <i class="flaticon2-reply-1" style="font-size: 1rem;"></i> @lang('dashboard.back')
                            </a>
                        </div>
                    </div>
                    <form novalidate="novalidate" class="kt-form kt-form--label-right" method="post" action="{{ route('dashboard.swalefs.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.title') </label> <span class="text-danger"> *</span>
                                        <div class="input-group">
                                            <input type="text" name="title" value="{{old("title")}}" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.title') " aria-describedby="basic-addon1" />
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('title') ? $errors->first('title') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.description') </label> <span class="text-danger"> *</span>
                                        <div class="input-group">
                                            <textarea class="description form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description">{{ old('description') }}</textarea>
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('description') ? $errors->first('description') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang("dashboard.type")</label>
                                        <span class="text-danger"> *</span>
                                        <select id="type" class="form-control select2 " name="type">
                                            <option value="">@lang('dashboard.select_type')</option>
                                            @foreach($types as $k => $type)
                                            <option value="{{$type}}" data-name="{{strtolower($type)}}" {{ $type == old("type") ? 'selected' : '' }}>
                                                {{$k}}
                                            </option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger" style="margin-right: 6px !important; margin-top: 11px; ">
                                            <strong>{{ $errors->has('type') ? $errors->first('type') : '' }}</strong>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6" id="content_section" style="display: none;">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.content') </label> <span class="text-danger"> *</span>
                                        <div class="input-group">
                                            <textarea  style="display: none;"  id="textarea" class="content form-control {{ $errors->has('content') ? 'is-invalid' : '' }}" name="content">{{ old('content') }}</textarea>

                                            <input  style="display: none;" id="text" type="text" name="content"  class="form-control {{ $errors->has('content') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.content') " />

                                            <div  style="display: none;" class="row col-md-12 file_input" id="file">
                                                <input type="file" id="file" name="content" class="file_input form-control col-md-10 {{ $errors->has('content') ? 'is-invalid' : '' }}" placeholder="{{__('dashboard.enter')}} {{__('dashboard.swalef_content')}}" aria-describedby="basic-addon1">
                                                <div class="col-md-2 image">
                                                    <div class="image_prev_form thumb-output">
                                                        <img src="{{resolvePhoto('dashboard_assets/media/swalefs/default.jpg')}}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('content') ? $errors->first('content') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="col-md-6">
                                    <div class="form-group row validated">
                                        <div class="col-md-10">
                                            <label>{{__('dashboard.image')}}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">
                                                        <i class="flaticon2-image-file"></i>
                                                    </span>
                                                </div>
                                                <input type="file" name="image" class="form-control file {{ $errors->has('image') ? 'is-invalid' : '' }}" placeholder="{{__('dashboard.enter')}} {{__('dashboard.swalef_image')}}" aria-describedby="basic-addon1">
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('image') ? $errors->first('image') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 image">
                                            <div class="image_prev_form thumb-output">
                                                <img src="{{resolvePhoto('dashboard_assets/media/swalefs/default.jpg')}}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <span class="switch switch-outline switch-icon switch-success">
                                        <label style="margin:15px">@lang('dashboard.status') : </label>
                                        <label>
                                            <input type="checkbox"  {{old('active' , '') ? 'checked' : ''}} name="active"  />
                                            <span></span>
                                        </label>
                                    </span>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">@lang('dashboard.submit')</button>
                            <button type="reset" class="btn btn-secondary">@lang('dashboard.cancel')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection