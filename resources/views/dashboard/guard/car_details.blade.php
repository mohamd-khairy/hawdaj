@extends('layouts.dashboard.master')
@push('js')
    <script src="{{asset('dashboard_assets/custom/js/guard.js')}}" type="text/javascript"></script>
@endpush

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.guard')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">{{$title}}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="row justify-content-md-center">
                <div class="col-12 col-md-3">
                    <ul class="nav nav-pills scroll-sm-nav-pills flex-md-column rounded bg-white" id="myTab1"
                        role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="car-request-tab-1" data-toggle="tab" href="#car-request-1">
                                                <span class="nav-icon">
                                                    <!-- <i class="flaticon2-user"></i> -->
                                                    <i class="flaticon2-check-mark "></i>
                                                </span>
                                <span class="nav-text">{{ __('dashboard.car_request') }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="car-tab-1" data-toggle="tab" href="#car-1">
                                                <span class="nav-icon">
                                                    <!-- <i class="flaticon2-user"></i> -->
                                                    <i class="flaticon2-check-mark "></i>
                                                </span>
                                <span class="nav-text">{{ __('dashboard.car_data') }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="contact-tab-1" data-toggle="tab" href="#contact-1">
                                                <span class="nav-icon">
                                                    <!-- <i class="flaticon2-user"></i> -->
                                                    <i class="flaticon2-check-mark "></i>
                                                </span>
                                <span class="nav-text">{{ __('dashboard.driver') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-12 col-md-9">
                    <div class="card-body rounded bg-white">
                        <div class="tab-content mt-5" id="myTabContent1">
                            <div class="tab-pane fade show active" id="car-request-1" role="tabpanel" aria-labelledby="car-request-tab-1">
                                <!-- first tab form -->
                            @include('dashboard.guard.tabs.car_request')
                            <!-- end first tab form -->
                            </div>
                            <div class="tab-pane fade" id="car-1" role="tabpanel" aria-labelledby="car-tab-1">
                                <!-- first tab form -->
                            @include('dashboard.guard.tabs.car_data')
                            <!-- end first tab form -->
                            </div>
                            <!-- permission tab -->
                            <div class="tab-pane fade" id="contact-1" role="tabpanel" aria-labelledby="contact-tab-1">
                                @include('dashboard.guard.tabs.car_contact')
                            </div>
                            <!-- end permission tab -->
                        </div>
                        <!-- End tabs -->
                        <!-- take action -->
                        <div class="mt-4" >
                            <a type="button" id="guardtakeCarAction" class="btn btn-success">{{ __('dashboard.take_action') }}</a>
                        </div>
                        <!-- end take action -->
                    </div>
                </div>
            </div>
        </div>
    </div>




<!--begin::page-->
{{--    <div class="d-flex flex-column-fluid">--}}
{{--        <div class="container-fluid">--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-12 bg-white">--}}
{{--                    <div class="row justify-content-md-center">--}}
{{--                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
{{--                            <div class="bg-white rounded p-10">--}}
{{--                                    <!-- steps -->--}}
{{--                                <div class="example-preview">--}}
{{--                                    <ul class="nav nav-pills" id="myTab1" role="tablist">--}}
{{--                                        <li class="nav-item">--}}
{{--                                            <a class="nav-link active" id="car-request-tab-1" data-toggle="tab" href="#car-request-1">--}}
{{--                                                <span class="nav-icon">--}}
{{--                                                    <!-- <i class="flaticon2-user"></i> -->--}}
{{--                                                    <i class="flaticon2-check-mark text-success"></i>--}}
{{--                                                </span>--}}
{{--                                                <span class="nav-text">{{ __('dashboard.car_request') }}</span>--}}
{{--                                            </a>--}}
{{--                                        </li>--}}
{{--                                        <li class="nav-item">--}}
{{--                                            <a class="nav-link" id="car-tab-1" data-toggle="tab" href="#car-1">--}}
{{--                                                <span class="nav-icon">--}}
{{--                                                    <!-- <i class="flaticon2-user"></i> -->--}}
{{--                                                    <i class="flaticon2-check-mark text-success"></i>--}}
{{--                                                </span>--}}
{{--                                                <span class="nav-text">{{ __('dashboard.car_data') }}</span>--}}
{{--                                            </a>--}}
{{--                                        </li>--}}
{{--                                        <li class="nav-item">--}}
{{--                                            <a class="nav-link" id="contact-tab-1" data-toggle="tab" href="#contact-1">--}}
{{--                                                <span class="nav-icon">--}}
{{--                                                    <!-- <i class="flaticon2-user"></i> -->--}}
{{--                                                    <i class="flaticon2-check-mark text-success"></i>--}}
{{--                                                </span>--}}
{{--                                                <span class="nav-text">{{ __('dashboard.driver') }}</span>--}}
{{--                                            </a>--}}
{{--                                        </li>--}}
{{--                                    </ul>--}}
{{--                                    <div class="tab-content mt-5" id="myTabContent1">--}}
{{--                                        <div class="tab-pane fade show active" id="car-request-1" role="tabpanel" aria-labelledby="car-request-tab-1">--}}
{{--                                            <!-- first tab form -->--}}
{{--                                            @include('dashboard.guard.tabs.car_request')--}}
{{--                                            <!-- end first tab form -->--}}
{{--                                        </div>--}}
{{--                                        <div class="tab-pane fade" id="car-1" role="tabpanel" aria-labelledby="car-tab-1">--}}
{{--                                            <!-- first tab form -->--}}
{{--                                            @include('dashboard.guard.tabs.car_data')--}}
{{--                                            <!-- end first tab form -->--}}
{{--                                        </div>--}}
{{--                                        <!-- permission tab -->--}}
{{--                                        <div class="tab-pane fade" id="contact-1" role="tabpanel" aria-labelledby="contact-tab-1">--}}
{{--                                            @include('dashboard.guard.tabs.car_contact')--}}
{{--                                        </div>--}}
{{--                                        <!-- end permission tab -->--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <!-- End tabs -->--}}
{{--                                <!-- take action -->--}}
{{--                                <div class="text-center" style="margin-top: -30px">--}}
{{--                                    <a type="button" id="guardtakeCarAction" class="btn btn-outline-success">{{ __('dashboard.take_action') }}</a>--}}
{{--                                </div>--}}
{{--                                <!-- end take action -->--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
<!-- begin::page -->
<!-- Modal-->
@include('dashboard.guard.carAction')
<!-- end Modal -->
@endsection
