@extends('layouts.front.hawdaj_master')
@section('content')
    <section class="zad section-padding">
        <div class="container row">
            <div class="mb-4 ">
                <h2 class="section__title"> رحلاتـــــــــــك
                </h2>
            </div>
        </div>


        <div class="container px-0">
            @if ($trips)
                <div class="zad__grid-container">
                    <div class="row mx-0">
                        @foreach ($trips as $place)
                            <div class="col-3 mb-4 d-flex">
                                <a href="{{ url('view_trip/' . $place['id']) }}" class="palce-card card h-100"
                                    style="width:300px">

                                    <!-- card img -->
                                    <img src="{{ asset('front_assets/imgs/zad1.jpg') }}" class="card-img-top"
                                        alt="place">

                                    <!-- card content -->
                                    <div class="card-body pb-4">
                                        <h5 class="card-title">{{ $place['name'] }} </h5>
                                        <div class="d-flex align-items-center">
                                            <div class="rate d-flex align-items-center">
                                                <span class="mx-1">{{ $place['date'] }}</span>
                                            </div>
                                        </div>
                                        <span class="views m-3 pt-4">({{ $place['days'] }} يوم) ,
                                            ({{ $place['item_per_day'] }} مكان في اليوم)
                                        </span>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center alert alert-danger">لا يوجد نتائج لهذه الرحلة</div>
            @endif
        </div>

    </section>
@endsection
