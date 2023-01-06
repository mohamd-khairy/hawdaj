<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('front_assets/imgs/logo.svg') }}" type="image" sizes="16x16">

    @if (isset($place) && isset($place->ceo))
        <title>هودج | {{ $place->ceo->title ?? '' }}</title>
    @elseif (isset($place))
        <title>هودج | {{ $place->title ?? '' }}</title>
    @else
        <title>هودج | المملكة العربية السُّعُودية بين الماضي والحاضر</title>
    @endif

    <!-- SEO Meta -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base_url" content="{{ asset('/') }}">
    <link rel="canonical" href="{{ url('/') }}" />

    @if (isset($place) && isset($place->ceo))
        <meta name="description" content="{{ $place->ceo->description ?? '' }}">
        <meta name="keywords" content="{{ $place->ceo->key_words ?? '' }}">
        <meta name="robots" content="{{ $place->ceo->key_words ?? '' }}">
        <meta property="og:type" content="{{ $place->ceo->type ?? '' }}" />
        <meta property="og:title" content="{{ $place->ceo->title ?? '' }}" />
        <meta property="og:description" content="{{ $place->ceo->description ?? '' }}" />
        <meta property="og:image" content="{{ asset($place->image) ?? '' }}" />
        <meta property="og:url" content="{{ asset($place->ceo->link) ?? '' }}" />
        <meta property="og:site_name" content="Hwdaj" />
    @endif
    <style>
        /* ********************* */
        /* scrollbar style start */
        ::-webkit-scrollbar {
            width: 10px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #2c085d;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* scrollbar style end */
        /* ******************* */
        .loader-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 99999;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #2C085D;
            transition: 300ms ease;
        }

        .loader-container.hide {
            transform: scale(8);
            opacity: 0;
            visibility: hidden;
        }

        .loader {
            width: 128px;
            height: 128px;
            border: 3px solid #fff;
            border-bottom: 3px solid transparent;
            border-radius: 50%;
            position: relative;
            -webkit-animation: spin 1s linear infinite;
            animation: spin 1s linear infinite;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .loader .inner {
            width: 64px;
            height: 64px;
            border: 3px solid transparent;
            border-top: 3px solid #fff;
            border-radius: 50%;
            -webkit-animation: spinInner 1s linear infinite;
            animation: spinInner 1s linear infinite;
        }

        @-webkit-keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @-webkit-keyframes spinInner {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(-720deg);
            }
        }

        @keyframes spinInner {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(-720deg);
            }
        }
    </style>
    @yield('style')
    <link rel="stylesheet" href="{{ asset('front_assets/css/vendor/vendor.bundle.css') }}">
    <!-- swiper -->
    <link rel="stylesheet" href="{{ asset('front_assets/libs/swiper/swiper-bundle.min.css') }}">
    <!-- intlTelInput -->
    <link rel="stylesheet" href="{{ asset('front_assets/libs/intlTelInput/css/intlTelInput.min.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('front_assets/css/main.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front_assets/css/main-style.css') }}">
    <script>
        // initMap declaration
        // to hide 'initMap is not a function' error
        function initMap() {}
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0- 
     alpha/css/bootstrap.css"
        rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</head>

<body class="overflow-hidden">

    <!-- loader -->
    <div class="loader-container">
        <div class="loader">
            <div class="inner"></div>
        </div>
    </div>

    @if (!isset($map_most_pupular_places))
        @if (isset($place) || isset($places) || isset($store) || isset($stores))
            @include('layouts.front.partials.header_place')
        @else
            @include('layouts.front.partials.header')
        @endif
    @endif


    @yield('content')


    @if (!isset($map_most_pupular_places))
        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 logo">
                        <div class="d-flex align-items-center mb-4">
                            <img width="150" src="{{ asset('front_assets/imgs/logo.svg') }}" alt="هودج">
                            <span
                                class="tajawal-bold heading">{{ $settings->where('group', 'main_services')->where('key', 'SECTION_TITLE')->first()->value ?? 'test' }}</span>
                        </div>
                        <p class="mb-4">
                            {{ $settings->where('group', 'main_services')->where('key', 'SECTION_DESCRIPTION')->first()->value ?? 'test' }}
                        </p>
                        <div class="mb-4 mb-lg-0">
                            <h6 class="heading mb-2 tajawal-bold">
                                <span></span>
                                تابعنا على
                            </h6>
                            <ul class="social-media d-flex align-items-center">
                                <li>
                                    <a href="{{ $settings->where('group', 'app')->where('key', 'INSTGRAM')->first()->value ?? '#' }}"
                                        target="_blank">
                                        <span>Instagram</span>
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1.3rem" height="1.3rem"
                                                viewBox="0 0 22.999 23">
                                                <g transform="translate(-873.501 -1199.5)">
                                                    <path
                                                        d="M-3647.875-6713.5h-8.249a6.883,6.883,0,0,1-6.875-6.875v-8.251a6.883,6.883,0,0,1,6.875-6.875h8.249a6.883,6.883,0,0,1,6.875,6.875v8.251A6.882,6.882,0,0,1-3647.875-6713.5ZM-3656-6733a5.006,5.006,0,0,0-5,5v7a5.006,5.006,0,0,0,5,5h8a5.006,5.006,0,0,0,5-5v-7a5.006,5.006,0,0,0-5-5Z"
                                                        transform="translate(4537 7935.5)" fill="#f9f6e5"
                                                        stroke="rgba(0,0,0,0)" stroke-miterlimit="10"
                                                        stroke-width="1" />
                                                    <path d="M5.5,0A5.5,5.5,0,1,0,11,5.5,5.5,5.5,0,0,0,5.5,0Z"
                                                        transform="translate(879.5 1205.5)" fill="#f9f6e5" />
                                                    <path
                                                        d="M3.438,6.875A3.438,3.438,0,1,1,6.875,3.438,3.442,3.442,0,0,1,3.438,6.875Z"
                                                        transform="translate(881.563 1207.563)" fill="#f9f6e5" />
                                                    <circle cx="1.375" cy="1.375" r="1.375"
                                                        transform="translate(889.583 1203.667)" fill="#f9f6e5" />
                                                </g>
                                            </svg>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ $settings->where('group', 'app')->where('key', 'TWITTER')->first()->value ?? '#' }}"
                                        target="_blank">
                                        <span>Twitter</span>
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1.3rem" height="1.3rem"
                                                viewBox="0 0 22 18">
                                                <path
                                                    d="M22,2.131a8.958,8.958,0,0,1-2.593.715A4.551,4.551,0,0,0,21.392.332a9,9,0,0,1-2.866,1.1,4.51,4.51,0,0,0-7.809,3.11,4.566,4.566,0,0,0,.117,1.036A12.784,12.784,0,0,1,1.531.831,4.569,4.569,0,0,0,2.928,6.9,4.459,4.459,0,0,1,.884,6.329c0,.019,0,.039,0,.058A4.539,4.539,0,0,0,4.5,10.842a4.5,4.5,0,0,1-2.038.079,4.522,4.522,0,0,0,4.216,3.156,9.017,9.017,0,0,1-5.606,1.945A9.028,9.028,0,0,1,0,15.958,12.7,12.7,0,0,0,6.918,18,12.8,12.8,0,0,0,19.761,5.07c0-.2,0-.393-.013-.588A9.188,9.188,0,0,0,22,2.131Z"
                                                    fill="#f9f6e5" />
                                            </svg>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ $settings->where('group', 'app')->where('key', 'FACEBOOK')->first()->value ?? '#' }}"
                                        target="_blank">
                                        <span>Facebook</span>
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="0.8rem"
                                                viewBox="0 0 10.798 19.401">
                                                <path
                                                    d="M10.392,0,7.8,0a4.482,4.482,0,0,0-4.79,4.775v2.2H.407a.4.4,0,0,0-.407.4v3.19a.4.4,0,0,0,.407.4h2.6v8.048a.4.4,0,0,0,.407.4h3.4a.4.4,0,0,0,.407-.4V10.957h3.045a.4.4,0,0,0,.407-.4V7.372a.391.391,0,0,0-.119-.28.413.413,0,0,0-.288-.116H7.224V5.11c0-.9.22-1.352,1.423-1.352h1.745a.4.4,0,0,0,.407-.4V.4A.4.4,0,0,0,10.392,0Z"
                                                    transform="translate(0)" fill="#f9f6e5" />
                                            </svg>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-8 site-links">
                        <div class="row w-100">
                            {{-- <div class="col-6 col-sm-4 col-md-3 mb-4 mb-md-0">
                                <div>
                                    <h6 class="heading tajawal-bold">
                                        <span></span>
                                        تأجير كرفان
                                    </h6>
                                    <ul>
                                        <li>
                                            <a href="/caravan.html">
                                                الأقرب لك
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                المتاح حاليا
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                فئات الكرفان
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                أسعار التأجير
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                كرفان مميز
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div> --}}
                            <div class="col-6 col-sm-4 col-md-3 mb-4 mb-md-0">
                                <div>
                                    <h6 class="heading tajawal-bold">
                                        <span></span>
                                        المتاجر
                                    </h6>
                                    <ul>
                                        @foreach (\App\Models\CategoryOfStore::whereNull('parent_id')->take(5)->get() as $s_cat)
                                            <li>
                                                <a href="#">
                                                    {{ $s_cat->name ?? '' }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                            </div>
                            <div class="col-6 col-sm-4 col-md-3">
                                <div>
                                    <h6 class="heading tajawal-bold">
                                        <span></span>
                                        الأماكن المميزة
                                    </h6>
                                    <ul>
                                        @foreach (\App\Models\Category::whereNull('parent_id')->take(5)->get() as $s_cat)
                                            <li>
                                                <a href="#">
                                                    {{ $s_cat->name ?? '' }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            {{-- <div class="col-6 col-md-3">
                                <div>
                                    <h6 class="heading tajawal-bold">
                                        <span></span>
                                        طلب خاص
                                    </h6>
                                    <ul>
                                        <li>
                                            <a href="#">
                                                تقديم المواصفات
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                احتساب التكلفة
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="year">
      <div class="container">
        <p class="mb-0 d-flex justify-content-start">
            &copy; 2022
        </p>
      </div>
    </div> -->

        </footer> <!-- footer -->
    @endif

    @include('front.trip.make_trip')
    @include('front.trip.login_popup')
    <!-- make a trip popup -->

    <!-- highcharts -->
    <script src="https://code.highcharts.com/maps/highmaps.js"></script>
    <!-- select2 plugin -->
    <!-- google map -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB91YG1p1wUm3qn1viU0Y8d-uoH9htIKLs&callback=initMap">
    </script>
    <!-- vendor js bundle -->
    <script src="{{ asset('front_assets/js/vendor/vendor.bundle.js') }}"></script>
    <!-- swiper -->
    <script src="{{ asset('front_assets/libs/swiper/swiper-bundle.min.js') }}"></script>
    <!-- intlTelInput-jquery -->
    <script src="{{ asset('front_assets/libs/intlTelInput/intlTelInput-jquery.min.js') }}"></script>

    <!-- share buttons -->
    <script src="{{ asset('front_assets/libs/share-buttons/share-buttons.js') }}"></script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script>
        $(document).ready(function() {
            new Swiper(".our-services-slider", {
                cssMode: true,
                // loop: true,
                autoplay: {
                    delay: 2500,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                mousewheel: true,
                keyboard: true,
            });

            // telInput plugin
            $("#phone").intlTelInput({
                initialCountry: 'SA'
            });
        });
    </script>

    @yield('scripts')

    <script src="{{ asset('front_assets/js/main.bundle.js') }}"></script>

    <!-- <script src="{{ asset('dashboard_assets/js/dmukaZoom/make_a_trip_popup.js') }}"></script> -->

    <script>
        $(document).ready(function() {
            @if (session()->get('show_trip') !== 0)
                $('.popup_that_shows_on_startup #make_a_trip_popup').addClass('hide')
                $('.popup_that_shows_on_startup #popup_background').addClass('hide')
            @else
                makeATripPopupShow()
            @endif
        })


        // *******************************
        // *******************************
        /* popup back and next buttons */
        var popup_input_values = true
        function makeATripNextTab(tabNum, button_direction) {
            var val = -(tabNum * 100),
                childArray = [],
                newChildArray = [],
                childCounter = 1;
            popup_input_values = true
            if(button_direction == 'next'){
                $(`.popup_that_shows_on_startup #make_a_trip_popup .tabs > .tab:nth-of-type(${tabNum}) .left-side`).children('input[required], select[required]').each(function(){
                    if($(this).val()){}else{
                        popup_input_values = false;
                        // childArray.push(childCounter)
                        childArray.push(this)
                    }
                    // alert('gere')
                    // childCounter = childCounter + 1;
                })
                if(popup_input_values == false){
                    // alert("{{__('dashboard.fill_required_fields')}}")
                    // alert(childArray)

                    for(i = 0 ; i < childArray.length ; i++){
                        if (!$(childArray[i]).prev('label').children('.please_fill_this_field').length){
                            $(childArray[i]).prev('label').append(" <b class='please_fill_this_field'> {{__('dashboard.please_fill_this_field')}} </b> ")
                        }
                    }

                    // childCounter = 1
                    // $(`.popup_that_shows_on_startup #make_a_trip_popup .tabs > .tab:nth-of-type(${tabNum}) .left-side`).children('input[required], select[required]').each(function(){
                    //     if(jQuery.inArray(childCounter, childArray)){
                    //         alert(childCounter)
                    //     }
                    //     childCounter = childCounter + 1
                    // })
                    // $( "p" ).before( "<b>Hello</b>" );
                }
            }
            if(popup_input_values == true){
                $('.popup_that_shows_on_startup #make_a_trip_popup .tabs').css("transition", "all 0.3s")
                $('.popup_that_shows_on_startup #make_a_trip_popup .tabs').css("opacity", "0")
                $('.popup_that_shows_on_startup #make_a_trip_popup .loader_infinity').removeClass('hide')
                setTimeout(() => {
                    $('.popup_that_shows_on_startup #make_a_trip_popup .tabs').css("transform", `translateX(${val}%)`)
                }, 300);
                setTimeout(() => {
                    $('.popup_that_shows_on_startup #make_a_trip_popup .loader_infinity').addClass('hide')
                    $('.popup_that_shows_on_startup #make_a_trip_popup .tabs').css("opacity", "1")
                }, 600);
                // the following part is to control length of pages in popup
                if (tabNum == 4) {
                    $('.popup_that_shows_on_startup #make_a_trip_popup .tab.registerPage').addClass('hide')
                } else if (tabNum == 5) {
                    $('.popup_that_shows_on_startup #make_a_trip_popup .tab.registerPage').removeClass('hide')
                }
            }
        }

        // *******************************
        // *******************************
        /* show hide popup */
        function makeATripPopupShow() {
            setTimeout(() => {
                $('.popup_that_shows_on_startup').removeClass('hide')
            }, 200);
            $('.popup_that_shows_on_startup').click(function(e) {
                var $target = $(e.target);
                if($target.hasClass("closing_x")){
                        $('.popup_that_shows_on_startup').addClass('hide')
                    }
                // alert(e.target.className)
                if (!$target.closest('#make_a_trip_popup').length) {
                    if(!$target.hasClass("popup_that_shows_on_startup")){}else{
                        // $('#make_a_trip_popup .select2 .select2-selection--multiple').click(function(){
                        // })
                        $('.popup_that_shows_on_startup').addClass('hide')
                    }
                }
                // alert('hi')
            })
        }

        function makeALoginPopupShow() {
            setTimeout(() => {
                $('.popup_login').removeClass('hide')
            }, 200);
            $('.popup_login').click(function(e) {
                var $target = $(e.target);
                if (!$target.closest('#make_a_trip_popup').children().length) {
                    $('.popup_login').addClass('hide')
                }
                // alert('hi')
            })
        }


        // *******************************
        // *******************************
        /* only allow next when all required inputs are full */
        
        // $(".popup_that_shows_on_startup #make_a_trip_popup input[required], .popup_that_shows_on_startup #make_a_trip_popup select[required]")
        //     .on("input", function() {
        //         // alert('changed')
        //         if ($(this).val()) {
        //             $(this).siblings('input[required], select[required]').each(function(index) {
        //                 if ($(this).val()) {
        //                     alert($(this).val())
        //                     popup_input_values = true;
        //                 } else {
        //                     popup_input_values = false;
        //                     // $(this).siblings('div.navigation_buttons').children('button.next').prop('disabled',
        //                     //     true);
        //                     // $(this).siblings('#login, #register').prop('disabled', true);
        //                     // alert(popup_input_values); 
        //                     return;
        //                 }
        //             })
        //         } else {
        //             // $(this).siblings('div.navigation_buttons').children('button.next').prop('disabled', true);
        //             // $(this).siblings('#login, #register').prop('disabled', true);
        //             popup_input_values = false;
        //             alert(popup_input_values); 
        //         }
        //         if (popup_input_values) {
        //             popup_input_values = true;
        //             // $(this).siblings('div.navigation_buttons').children('button.next').prop('disabled', false);
        //             // $(this).siblings('#login, #register').prop('disabled', false);
        //         }
        //         // console.log(popup_input_values)
        //     });

        $('.popup_that_shows_on_startup #make_a_trip_popup button#as_a_guest').click(function() {
            // alert('clicked')
            $('.popup_that_shows_on_startup #make_a_trip_popup input[required]').prop('required', false);
        })


        $(document).on('change', '#region_id', function() {
            // get cities
            const region_id = $(this).val()

            $.ajax({
                type: "GET",
                url: "{{ route('cities') }}",
                data: {
                    region_id: region_id
                },
                success: function(data) {
                    $('#city_id').empty();
                    $('#city_id').append(data);
                }
            });
        })


        $(document).on('click', '#login', function() {
            $('#type').val('login');
            $('#trip_form').submit();
        });
        $(document).on('click', '#register', function() {
            $('#type').val('register');
            $('#trip_form').submit();
        });
    </script>
    <script>
        /* show hide register and login popup */
        function openHomeRegisterAndLoginPopup() {
            setTimeout(() => {
                $('.home_login_and_register_form_container').removeClass('hide')
            }, 200);
            $('.home_login_and_register_form_container').click(function(e) {
                var $target = $(e.target);
                if (!$target.closest('#home_login_and_register_form').length) {
                    $('.home_login_and_register_form_container').addClass('hide')
                }
                // alert('hi')
            })
        }

        // toggle register and login pages
        function toggleLoginAndRegisterPages() {
            $('#home_login_and_register_form > form').toggleClass('hide')
        }
    </script>

    @if (session('message'))
        <script>
            toastr.success("{{ session('message') }}");
        </script>
    @endif

    @if (session('error'))
        <script>
            toastr.error("{{ session('error') }}");
        </script>
    @endif

    @if ($errors->count() > 0)
        @foreach ($errors->all() as $error)
            <script>
                toastr.error("{{ $error }}");
            </script>
        @endforeach
    @endif
</body>

</html>
