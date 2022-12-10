 <!-- header -->
 <header class="header home overflow-hidden">
     <!-- bg slider -->
     <div class="header__bg-slider swiper">
         <div class="swiper-wrapper">
             @if (isset($places_data))
                 @foreach ($places_data as $place)
                     <div class="swiper-slide">
                         <img src="{{ asset($place->image) }}">
                     </div>
                 @endforeach
             @endif
         </div>
     </div>
     <div class="header__content container px-0 px-sm-3">
         <!-- top navbar -->
         @include('layouts.front.partials.nav')

         <div class="row header__body align-items-center">
             <div class="col-lg-4 header__body--text d-flex flex-column justify-content-center mb-5 mb-lg-0">
                 <h1 class="slide-up">
                     <span>هـــــــــــــودج</span>
                 </h1>
                 <p class="mb-4 tajawal-bold slide-up">
                     المملكة العربية السعودية بين المــــــــاضي والحــــــاضــــــر
                 </p>
                 <div class="mb-2 slide-up">
                     <a href="/places" class="btn btn-discover">
                         <span class="tajawal-bold">اكتشف المزيد</span>
                         <span>
                             <svg width="2rem" height="1rem" viewBox="0 0 62 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                 <path
                                     d="M60 13.5C60.8284 13.5 61.5 12.8284 61.5 12C61.5 11.1716 60.8284 10.5 60 10.5L60 13.5ZM0.939339 10.9393C0.353554 11.5251 0.353554 12.4749 0.939339 13.0607L10.4853 22.6066C11.0711 23.1924 12.0208 23.1924 12.6066 22.6066C13.1924 22.0208 13.1924 21.0711 12.6066 20.4853L4.12132 12L12.6066 3.51472C13.1924 2.92894 13.1924 1.97919 12.6066 1.3934C12.0208 0.807616 11.0711 0.807616 10.4853 1.3934L0.939339 10.9393ZM60 10.5L2 10.5L2 13.5L60 13.5L60 10.5Z"
                                     class="fill" fill="#fff" />
                             </svg>
                         </span>
                     </a>
                 </div>
             </div>
             <div class="col-lg-8 px-0 slide-up">
                 <div class="header__slider mb-3">
                     @if (isset($places_data))
                         @foreach ($places_data as $place)
                             <div>
                                 <a href="/place-details/{{ $place->id }}" style="color: white">
                                     <div class="header__slider-card">
                                         <img src="{{ asset($place->image) }}" alt="">
                                         <div class="header__slider-card-text">
                                             <span>{{ $place->city ? $place->city->name : '' }}</span>
                                             <h2> {{ $place->title }}</h2>
                                         </div>
                                     </div>
                                 </a>
                             </div>
                         @endforeach
                     @endif

                 </div>
                 @if (isset($places_data) && $places_data->count() > 3)
                     <div class="header__slider-footer d-flex align-items-center py-3">
                         <div class="header__slider-navigator d-flex align-items-center">
                             <button id="headerSliderNext" class="btn">
                                 <svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                     <path fill-rule="evenodd" clip-rule="evenodd"
                                         d="M0.707158 8.82285C0.307803 8.76787 -1.6461e-06 8.42047 -1.60935e-06 8.00011C-1.56927e-06 7.54154 0.366311 7.16979 0.81818 7.16979L15.1999 7.16979L10.0047 1.91866L9.92528 1.8257C9.68696 1.50114 9.71243 1.0398 10.0023 0.744413C10.3212 0.419482 10.8392 0.418394 11.1594 0.741982L17.7477 7.40045C17.787 7.43858 17.8224 7.48063 17.8536 7.52599C18.0766 7.85024 18.0452 8.30006 17.7593 8.58894L11.1593 15.2581L11.0674 15.3383C10.7466 15.5787 10.2921 15.551 10.0022 15.2555C9.68342 14.9306 9.68453 14.4049 10.0047 14.0813L15.2012 8.83043L0.81818 8.83043L0.707158 8.82285Z"
                                         fill="white" />
                                 </svg>
                             </button>
                             <button id="headerSliderPrev" class="btn">
                                 <svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                     <path fill-rule="evenodd" clip-rule="evenodd"
                                         d="M17.2928 7.17715C17.6922 7.23213 18 7.57953 18 7.99989C18 8.45846 17.6337 8.83021 17.1818 8.83021H2.80007L7.99531 14.0813L8.07472 14.1743C8.31304 14.4989 8.28757 14.9602 7.9977 15.2556C7.67884 15.5805 7.1608 15.5816 6.84062 15.258L0.25226 8.59955C0.213041 8.56142 0.177555 8.51937 0.146416 8.47401C-0.0766354 8.14976 -0.0452156 7.69994 0.240669 7.41106L6.84067 0.741932L6.9326 0.661737C7.25342 0.421253 7.7079 0.449045 7.99775 0.744461C8.31658 1.06942 8.31546 1.59515 7.99526 1.91871L2.79877 7.16957L17.1818 7.16957L17.2928 7.17715Z"
                                         fill="white" />
                                 </svg>
                             </button>
                         </div>
                         <div class="line"></div>
                         <div>
                             <span id="headerSliderCount" class="number">
                                 01
                             </span>
                         </div>
                     </div>
                 @endif
             </div>
         </div>
     </div>
     <!-- header tail -->
     <div class="header__tail">
         <div class="container">
             <div class="partners row mx-auto bg-white">
                 <div class="col-4 d-flex align-items-center justify-content-center px-0">
                     <a href="https://www.moc.gov.sa" target="_blank">
                         <img src="{{ asset('front_assets/imgs/ministry_of_culture.png') }}" alt="وزارة الثقافة">
                     </a>
                 </div>
                 <div class="col-4 d-flex align-items-center justify-content-center px-2">
                     <a href="https://www.gea.gov.sa" target="_blank">
                         <img src="{{ asset('front_assets/imgs/general_entertainment_authority.png') }}"
                             alt="الهيئة العامة للترفيه">
                     </a>
                 </div>
                 <div class="col-4 d-flex align-items-center justify-content-center px-0">
                     <a href="https://mt.gov.sa/Pages/default.aspx" target="_blank">
                         <img src="{{ asset('front_assets/imgs/ministry_of_tourism.png') }}" alt="وزارة السياحة">
                     </a>
                 </div>
             </div>
         </div>
     </div>
 </header> <!-- /header -->
