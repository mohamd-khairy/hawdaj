@extends('layouts.front.hawdaj_master')
@section('content')
    <section class="zad section-padding">
        <div class="my_trips_section">
            <div class="container">
                <div class="row">
                    <div class="mb-4 ">
                        <h2 class="section__title"> رحلاتـــــــــــك
                        </h2>
                    </div>
                </div>
            </div>


            <div class="container px-0">
                @if ($trips)
                    <div class="zad__grid-container">
                        <div class="row mx-0">
                            @foreach ($trips as $place)
                                <div class="col-3 mb-4 d-flex">
                                    <a href="{{ url('view_trip/' . $place['id']) }}" class="palce-card my_trips_place_card card h-100"
                                        style="width:300px">

                                        <!-- card img -->
                                        <img src="{{ asset('front_assets/imgs/zad1.jpg') }}" class="card-img-top"
                                            alt="place">

                                        <!-- card content -->
                                        <div class="card-body pb-4">
                                            <h5 class="my_trips_card_title">{{ $place['name'] }} </h5>
                                            <div class="d-flex align-items-center">
                                                <div class="rate d-flex align-items-center">
                                                    <span class="mx-1 my_trips_card_date">{{ $place['date'] }}</span>
                                                </div>
                                            </div>
                                            <span class="my_trips_card_views m-3 pt-4">(<span class="my_trips_card_numbers">{{ $place['days'] }}</span> يوم) ,
                                                (<span class="my_trips_card_numbers">{{ $place['item_per_day'] }}</span> مكان في اليوم)
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
        </div>

    </section>
@endsection
