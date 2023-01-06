@extends('layouts.front.hawdaj_master')
@section('content')
    <section class="filter-grid-section filter-grid-section--height">
        <div class="container">
            <h2 class="page-title mb-4">الســوالـف</h2>
            <div class="section-boxes container">
                <div class="section-boxes-wrap">
                    <div class="section-title">
                        <div class="section-projects-overflow">
                            <div class="card-row">
                                @foreach ($swalefs as $swalef)
                                    <div class="card-seation m-4">
                                        <a href="{{ url(app()->getLocale() . '/swalef/' . $swalef->id) }}">
                                            <div class="swiper-zoom-container">
                                                <!-- <span class="wishlist"><i class="fa-regular fa-heart"></i></span> -->
                                                <img
                                                    src="{{ asset($swalef->image ?? 'front_assets/imgs/our-services.jpg') }}">
                                                <span class="date-card"> <i
                                                        class="fa-solid fa-calendar-days p-2"></i>{{ $swalef->created_at ? date('d-m-Y', strtotime($swalef->created_at)) : '' }}</span>
                                            </div>
                                            <div class="title-area">
                                                <div class="project-type"><span>{{ $swalef->title ?? '' }}</span></div>
                                                <div class="project-title">
                                                    <span>{{ $swalef->description ? Str::substr($swalef->description, 0, 40) : '' }}</span>
                                                </div>
                                                <div class="arte-type d-flex justify-content-between">
                                                    <span class="type-project"><span>{{ $swalef->active ? 'منشور' : 'غير منشور' }}</span></span>
                                                    {{-- <span class="rate-project">
                                                        <em class="rating-">
                                                            <i class="fa-regular fa-star"></i>
                                                            <i class="fa-regular fa-star"></i>
                                                            <i class="fa-regular fa-star"></i>
                                                            <i class="fa-regular fa-star"></i>
                                                            <i class="fa-regular fa-star"></i>
                                                        </em>
                                                    </span> --}}
                                                </div>
                                            </div>
                                            <div class="see-more-n">
                                                <span class="see-m-nd">
                                                   شاهد المزيد
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        {!! $swalefs->withQueryString()->links('vendor.pagination.custom') !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
