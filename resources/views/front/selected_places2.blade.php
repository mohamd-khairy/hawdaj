@extends('layouts.front.hawdaj_master')
@section('content')
<style>
    @import url("https://fonts.googleapis.com/css?family=Nunito&display=swap");

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: #f5f6f7;
        font-family: "Nunito", sans-serif;
    }

    main {
        height: 100vh;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        position: relative;
    }

    .stepper {
        width: 20rem;
        display: flex;
        justify-content: center;
        align-items: center;
        position: absolute;
        top: 5%;
    }

    .step--1,
    .step--2,
    .step--3,
    .step--4 {
        width: 5rem;
        padding: 0.5rem 0;
        background: #fff;
        color: #666;
        text-align: center;
    }

    .step--1,
    .step--2,
    .step--3 {
        border-right: 1px solid #666;
    }

    .form {
        background: #fff;
        text-align: center;
        position: absolute;
        width: 25rem;
        box-shadow: 0.2rem 0.2rem 0.5rem rgba(51, 51, 51, 0.2);
        display: none;
        border-radius: 1rem;
        overflow: hidden;
    }

    .form--header-container {
        background: linear-gradient(to right, rgb(51, 51, 51), #919191);
        color: #fff;
        height: 6rem;
        padding: 1rem 0;
        margin-bottom: 2rem;
    }

    .form--header-title {
        font-size: 1.4rem;
    }

    .form--header-text {
        padding: 0.5rem 0;
    }

    input,
    select {
        padding: 0.8rem;
        margin: auto;
        margin-top: 0.5rem;
        width: 20rem;
        display: block;
        border-radius: 0.5rem;
        outline: none;
        border: 1px solid #bdbdbb;
    }

    .form__btn {
        background: #333;
        color: #fff;
        outline: none;
        border: none;
        padding: 0.5rem 0.7rem;
        width: 7rem;
        margin: 1rem auto;
        border-radius: 0.9rem;
        text-transform: uppercase;
        font-weight: 700;
        cursor: pointer;
    }

    .form--message-text {
        width: 25rem;
        background: #fff;
        color: #444;
        padding: 2rem 1rem;
        text-align: center;
        font-size: 1.4rem;
        box-shadow: 0.2rem 0.2rem 0.5rem rgba(51, 51, 51, 0.2);
        animation: fadeIn 0.8s;
        border-radius: 1rem;
    }

    .form-active {
        z-index: 1000;
        display: block;
    }

    .form-active-animate {
        animation: moveRight 1s;
    }

    .form-inactive {
        display: block;
        animation: moveLeft 1s;
    }

    .step-active {
        background: #666;
        color: #fff;
        border: 1px solid #666;
    }
</style>
<main id="main">
    <div class="stepper">
        <div class="step--1 step-active">Step 1</div>
        <div class="step--2">Step 2</div>
        <div class="step--3">Step 3</div>
        <div class="step--4">Finish</div>
    </div>
    <form class="form form-active">
        <div class="form--header-container">
            <h1 class="form--header-title">
                Personal Info
            </h1>
        </div>
        <input type="text" placeholder="Name" id="name" />
        <input type="text" placeholder="Email" id="email" />
        <input type="date" placeholder="date" id="date" />
        <select name="region_id" id="region_id">
            <option value="">{{ __('dashboard.select_region') }}</option>
            @foreach($regions as $region)
            <option value="{{ $region->id }}" {{ old('region_id') == $region->id ? 'selected ': '' }}>{{ $region->name ?? '---' }}</option>
            @endforeach
        </select>
        <select name="city_id" id="city_id">
            <option value="">{{ __('dashboard.select_city') }}</option>
        </select>
        <button class="form__btn" id="btn-1">Next</button>
    </form>
    <form class="form">
        <div class="form--header-container">
            <h1 class="form--header-title">
                key words
            </h1>
        </div>

        <select name="season" id="season">
            @foreach($seasons as $item)
            <option value="{{$item}}">{{$item}}</option>
            @endforeach
        </select>
        <input type="number" placeholder="days" id="days" />
        <input type="number" placeholder="funny days" id="funny_days" />

        <select name="price" id="price">
            @foreach($prices as $item)
            <option value="{{$item->id}}">{{$item->name}}</option>
            @endforeach
        </select>


        <button class="form__btn" id="btn-2-prev">Previous</button>
        <button class="form__btn" id="btn-2-next">Next</button>
    </form>
    <form class="form">
        <div class="form--header-container">
            <h1 class="form--header-title">
                categories
            </h1>
        </div>

        <select name="key_words[]" id="key_words" multiple>
            @foreach($key_words as $item)
            <option value="{{$item}}">{{$item}}</option>
            @endforeach
        </select>

        <select name="categories[]" id="categories" multiple>
            @foreach($categories as $item)
            <option value="{{$item->id}}">{{$item->name}}</option>
            @endforeach
        </select>
        <button class="form__btn" id="btn-3">Submit</button>
    </form>
    <div class="form--message"></div>

</main>
<div class="row col-12" id="result"></div>

<script>
    const formBtn1 = document.querySelector("#btn-1")
    const formBtnPrev2 = document.querySelector("#btn-2-prev")
    const formBtnNext2 = document.querySelector("#btn-2-next")
    const formBtn3 = document.querySelector("#btn-3")
    const result = document.querySelector("#result")

    // Button listener of form 1
    formBtn1.addEventListener("click", function(e) {
        gotoNextForm(formBtn1, formBtnNext2, 1, 2)
        e.preventDefault()
    })

    // Next button listener of form 2
    formBtnNext2.addEventListener("click", function(e) {
        gotoNextForm(formBtnNext2, formBtn3, 2, 3)
        e.preventDefault()
    })

    // Previous button listener of form 2
    formBtnPrev2.addEventListener("click", function(e) {
        gotoNextForm(formBtnNext2, formBtn1, 2, 1)
        e.preventDefault()
    })

    // Button listener of form 3
    formBtn3.addEventListener("click", function(e) {
        e.preventDefault()

        document.querySelector(`.step--3`).classList.remove("step-active")
        document.querySelector(`.step--4`).classList.remove("step-active")
        formBtn3.parentElement.style.display = "none"
        // document.querySelector(".form--message").innerHTML = `
        //             <h1 class="form--message-text">Your account is successfully created </h1>
        //             `

        var key_words = $('#key_words option:selected')
            .toArray().map(item => item.value);
        var categories = $('#categories option:selected')
            .toArray().map(item => item.value);

        var name = document.getElementById("name").value
        var email = document.getElementById("email").value
        var date = document.getElementById("date").value
        var days = document.getElementById("days").value
        var funny_days = document.getElementById("funny_days").value
        var season = document.getElementById("season").value
        var price = document.getElementById("price").value
        var region_id = document.getElementById("region_id").value
        var city_id = document.getElementById("city_id").value

        $.ajax({
            type: "POST",
            url: "{{ route('front.action_selected_places') }}",
            data: {
                name: name,
                email: email,
                date: date,
                days: days,
                funny_days: funny_days,
                season: season,
                price: price,
                categories: categories,
                key_words: key_words,
                region_id: region_id,
                city_id: city_id,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                console.log(data);

                var r = ``;
                for (let index = 0; index < data.length; index++) {
                    const element = data[index];
                    r += `<h1 class="col-12"> day ${index + 1} </h1></br>`;
                    for (let index2 = 0; index2 < element.length; index2++) {
                        const place = element[index2];

                        r += `
                    <div class="map-card m-2" data-place-id="${place.id}"
                                                data-map-lat="${place.lat}" data-map-lng="${place.long}">
                                                <img class="map-card__img" src="{{ asset('') }}${(place.image ? place.image : 'front_assets/imgs/zad1.jpg')}">
                                                <div class="map-card__footer">
                                                    <h5 class="map-card__title">${place.title}</h5>
                                                    <p class="map-card__text">${(place.city ? place?.city?.name : '..')}, ${(place.region ? place?.region?.name : '..')}</p>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center">
                                                        <div class="rate d-flex align-items-center">
                                                            <span class="mx-1"><svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.90806 0.968665C7.55064 0.193793 6.44936 0.193793 6.09194 0.968665L4.97736 3.38508C4.83169 3.70089 4.5324 3.91833 4.18704 3.95928L1.54446 4.2726C0.697071 4.37307 0.356754 5.42046 0.983254 5.99983L2.93698 7.80658C3.19232 8.0427 3.30663 8.39454 3.23885 8.73565L2.72024 11.3457C2.55393 12.1827 3.44489 12.83 4.1895 12.4132L6.51156 11.1134C6.81503 10.9435 7.18497 10.9435 7.48844 11.1134L9.8105 12.4132C10.5551 12.83 11.4461 12.1827 11.2798 11.3457L10.7611 8.73565C10.6934 8.39454 10.8077 8.0427 11.063 7.80658L13.0167 5.99983C13.6432 5.42046 13.3029 4.37307 12.4555 4.2726L9.81296 3.95928C9.4676 3.91833 9.16831 3.70089 9.02264 3.38508L7.90806 0.968665Z" fill="#FFCA00"/></svg></span><span class="mx-1">${place.rate}</span></div>
                                                        <span class="views mr-3">(${place.review} مراجعة)</span>
                                                    </div>
                                                    <a href="/${place.type}-details/${place.id}" class="btn map-card__btn"><svg width="12" height="12" viewBox="0 0 5 9" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.8088 8.80184C4.93123 8.67495 5 8.50288 5 8.32347C5 8.14405 4.93123 7.97198 4.8088 7.8451L1.57628 4.49585L4.8088 1.14661C4.92776 1.019 4.99358 0.848082 4.99209 0.670675C4.9906 0.493269 4.92192 0.323565 4.80085 0.198114C4.67977 0.0726652 4.51598 0.00150681 4.34476 -3.52859e-05C4.17353 -0.00157642 4.00857 0.0666227 3.88541 0.189874L0.191199 4.01749C0.0687744 4.14437 0 4.31644 0 4.49585C0 4.67527 0.0687744 4.84734 0.191199 4.97422L3.88541 8.80184C4.00787 8.92868 4.17394 8.99994 4.34711 8.99994C4.52027 8.99994 4.68634 8.92868 4.8088 8.80184Z" fill="white"/></svg></a>
                                                    </div>
                                                </div>
                                                </div>`;
                    }
                }

                document.getElementById("main").style.display = 'none'
                result.innerHTML = r;
            }
        });

    })

    const gotoNextForm = (prev, next, stepPrev, stepNext) => {
        // Get form through the button
        const prevForm = prev.parentElement
        const nextForm = next.parentElement
        const nextStep = document.querySelector(`.step--${stepNext}`)
        const prevStep = document.querySelector(`.step--${stepPrev}`)
        // Add active/inactive classes to both previous and next form
        nextForm.classList.add("form-active")
        nextForm.classList.add("form-active-animate")
        prevForm.classList.add("form-inactive")
        // Change the active step element
        prevStep.classList.remove("step-active")
        nextStep.classList.add("step-active")
        // Remove active/inactive classes to both previous an next form
        // setTimeout(() => {
        prevForm.classList.remove("form-active")
        prevForm.classList.remove("form-inactive")
        nextForm.classList.remove("form-active-animate")
        // }, 1000)
    }
</script>
<script>
    $(document).on('change', '#region_id', function() {
        // get cities
        const region_id = $(this).val()

        $.ajax({
            type: "GET",
            url: "{{ route('dashboard.getCities') }}",
            data: {
                region_id: region_id
            },
            success: function(data) {
                $('#city_id').empty();
                $('#city_id').append(data);
            }
        });
    })
</script>
@endsection