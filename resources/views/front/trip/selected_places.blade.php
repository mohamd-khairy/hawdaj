@extends('layouts.front.hawdaj_master')
@section('content')

<section class="zad section-padding">
    <div class="container">
        <div class="mb-4 ">
            <h2 class="section__title">برنــــــــــــامج رحلتـــــــــــك
            </h2>
        </div>
    </div>

    <div class="container px-0">
        @if($places)
        <div class="zad__grid-container">
            @foreach ($places as $i => $d)
            <h1 class="col-12">يــــوم {{$i + 1}} </h1></br>

            <div class="row mx-0">
                @foreach ($d as $place)
                <div class="col-3 mb-4 d-flex">
                    <a href="{{ url('place-details/' . $place['id']) }}" class="palce-card card h-100" style="width:300px">
                        <!-- tooltip -->
                        <div class="palce-card__tooltip">
                            <img src="{{ asset($place['image'] ?? 'front_assets/imgs/zad1.jpg') }}" class="palce-card__tooltip--img" alt="place">
                            <div class="palce-card__tooltip--text">
                                <h5 class="map-card__title"> {{ $place['title'] }}</h5>
                                <p class="map-card__text">
                                    {{ isset($place['city']) ? $place['city']['name'] : '' }},
                                    {{ isset($place['region']) ? $place['region']['name'] : '' }}
                                </p>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rate d-flex align-items-center"><span class="mx-1"><svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M7.90806 0.968665C7.55064 0.193793 6.44936 0.193793 6.09194 0.968665L4.97736 3.38508C4.83169 3.70089 4.5324 3.91833 4.18704 3.95928L1.54446 4.2726C0.697071 4.37307 0.356754 5.42046 0.983254 5.99983L2.93698 7.80658C3.19232 8.0427 3.30663 8.39454 3.23885 8.73565L2.72024 11.3457C2.55393 12.1827 3.44489 12.83 4.1895 12.4132L6.51156 11.1134C6.81503 10.9435 7.18497 10.9435 7.48844 11.1134L9.8105 12.4132C10.5551 12.83 11.4461 12.1827 11.2798 11.3457L10.7611 8.73565C10.6934 8.39454 10.8077 8.0427 11.063 7.80658L13.0167 5.99983C13.6432 5.42046 13.3029 4.37307 12.4555 4.2726L9.81296 3.95928C9.4676 3.91833 9.16831 3.70089 9.02264 3.38508L7.90806 0.968665Z" fill="#FFCA00" />
                                            </svg></span><span class="mx-1">{{ $place['rate'] }}</span></div>
                                    <span class="views mr-3">({{ $place['review'] }} مراجعة)</span>
                                </div>
                                <p class="mb-1">{!! $place['description'] ?? '' !!}</p>
                            </div>
                        </div>

                        <!-- card img -->
                        <img src="{{ asset($place['image'] ?? 'front_assets/imgs/zad1.jpg') }}" class="card-img-top" alt="place">

                        <!-- card content -->
                        <div class="card-body pb-4">
                            <h5 class="card-title">{{ $place['title'] }} </h5>
                            <p class="card-text">{{ isset($place['city']) ? $place['city']['name'] : '' }},
                                {{ isset($place['region']) ? $place['region']['name'] : '' }}
                            </p>
                            <div class="d-flex align-items-center">
                                <div class="rate d-flex align-items-center">
                                    <span class="mx-1">
                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M7.90806 0.968665C7.55064 0.193793 6.44936 0.193793 6.09194 0.968665L4.97736 3.38508C4.83169 3.70089 4.5324 3.91833 4.18704 3.95928L1.54446 4.2726C0.697071 4.37307 0.356754 5.42046 0.983254 5.99983L2.93698 7.80658C3.19232 8.0427 3.30663 8.39454 3.23885 8.73565L2.72024 11.3457C2.55393 12.1827 3.44489 12.83 4.1895 12.4132L6.51156 11.1134C6.81503 10.9435 7.18497 10.9435 7.48844 11.1134L9.8105 12.4132C10.5551 12.83 11.4461 12.1827 11.2798 11.3457L10.7611 8.73565C10.6934 8.39454 10.8077 8.0427 11.063 7.80658L13.0167 5.99983C13.6432 5.42046 13.3029 4.37307 12.4555 4.2726L9.81296 3.95928C9.4676 3.91833 9.16831 3.70089 9.02264 3.38508L7.90806 0.968665Z" fill="#FFCA00" />
                                        </svg>
                                    </span>
                                    <span class="mx-1">{{ $place['rate'] }}</span>
                                </div>
                                <span class="views mr-3">({{ $place['review'] }} مراجعة)</span>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center alert alert-danger">لا يوجد نتائج لهذه الرحلة</div>
        @endif
    </div>

</section>
@endsection