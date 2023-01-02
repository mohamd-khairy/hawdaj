@extends('layouts.front.hawdaj_master')
@section('content')
    <section class="zad section-padding">
        <div class="my_trips_section outer_page">
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
                        <div class="d-flex flex-row flex-wrap mx-0">
                            @foreach ($trips as $place)
                                <div class="mb-4 d-flex">
                                    <a href="{{ url('view_trip/' . $place['id']) }}" class="palce-card my_trips_place_card h-100"
                                        style="width:300px">

                                        <!-- card img -->
                                        <img src="{{ asset('front_assets/imgs/popup_images/suitcase.png') }}" class="card-img-top"
                                            alt="place">

                                        <!-- card content -->
                                        <div class="card-body pb-4">
                                            <h5 class="my_trips_card_title">{{ $place['name'] }} </h5>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="rate d-flex align-items-center">
                                                    <span class="mx-1 my_trips_card_date">{{ $place['date'] }}</span>
                                                </div>
                                            </div>
                                            <span class="my_trips_card_views m-3 d-flex flex-column">
                                                <span>
                                                    (<span class="my_trips_card_numbers">{{ $place['days'] }}</span> يوم)
                                                </span>
                                                <span>
                                                    (<span class="my_trips_card_numbers">{{ $place['item_per_day'] }}</span> مكان في اليوم)
                                                </span>
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
