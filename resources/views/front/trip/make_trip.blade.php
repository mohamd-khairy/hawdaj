<!-- Include Twitter Bootstrap and jQuery: -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" type="text/css"/>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

 
<!-- Include the plugin's CSS and JS: -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/js/bootstrap-multiselect.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/css/bootstrap-multiselect.css" type="text/css"/>



<style>
    /* make a trip popup start */

    .popup_that_shows_on_startup #make_a_trip_popup{
        position: fixed;
        top: 0;
        left: 0;
        width: 70%;
        /* height: calc(100% - 100px); */
        background-color: #fff;
        z-index: 4;
        margin-top: 75px;
        margin-left: 15%;
        border-radius: 10px;
        box-shadow: 0 0 10px #000;
        padding: 25px 0 0;
        text-align: center;
        transition: all 0.5s;
        visibility: visible;
        opacity: 1;
        overflow: hidden;
    }

    .popup_that_shows_on_startup #make_a_trip_popup.hide{
        transition: all 0.5s;
        visibility: hidden;
        opacity: 0;
    }

    .popup_that_shows_on_startup #make_a_trip_popup h5{
        margin-bottom: 20px;
        font-weight: bold;
        width: 60%;
    }

    .popup_that_shows_on_startup #make_a_trip_popup .tabs{
        flex-wrap: nowrap;
    }

    .popup_that_shows_on_startup #make_a_trip_popup .main_tab, .popup_that_shows_on_startup #make_a_trip_popup .tab{
        display: flex;
        flex-direction: row;
        justify-content: space-evenly;
    }

    .popup_that_shows_on_startup #make_a_trip_popup .main_tab > .left-side > p.description{
        width: 75%;
        margin: 20px auto;
    }

    .popup_that_shows_on_startup #make_a_trip_popup .main_tab > .left-side > button.make_a_trip_button{
        border-radius: 50px;
        padding: 10px 20px;
        font-weight: bold;
        letter-spacing: 1px;
        margin: 5px 0 0;
        width: max-content;
        align-self: center;
        color: #2c085d;
        border-color: #2c085d;
    }

    .popup_that_shows_on_startup .tabs .left-side{
        padding-bottom: 25px;
        width: 60%;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
    }
    
    .popup_that_shows_on_startup .tabs .right-side{
        width: 40%;
        text-align: start;
    }
    
    .popup_that_shows_on_startup .tabs .right-side img{
        width: 100%;
        object-fit: contain;
    }

    .popup_that_shows_on_startup #make_a_trip_popup .main_tab > .left-side > button.make_a_trip_button:hover:not([disabled]),
    .popup_that_shows_on_startup #make_a_trip_popup .tab button.make_a_trip_button:hover:not([disabled]),
    .popup_that_shows_on_startup .tab > .left-side > button:hover:not([disabled]){
        background-color: #2c085d;
        color: #fff;
    }

    .popup_that_shows_on_startup #make_a_trip_popup .tab button.make_a_trip_button{
        border: solid 2px #000;
        font-weight: bold;
        width: 100px;
        border-radius: 50px;
        font-weight: bold;
    }

    .popup_that_shows_on_startup #popup_background{
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: #000000bd;
        z-index: 999998;
    }

    .popup_that_shows_on_startup #popup_background.hide{
        transition: all 0.5s;
        visibility: hidden;
        opacity: 0;
    }

    .popup_that_shows_on_startup .tab{
        display: flex;
        flex-direction: column;
        width: 100%;
    }

    .popup_that_shows_on_startup .tab > .left-side > *:not(h6):not(.trip_categories):not(a):not(.navigation_buttons):not(label), #make_a_trip_popup .tab .trip_categories > span{
        width: 70%;
        margin: 0 auto;
        height: 40px;
        border-radius: 10px;
        padding: 5px 10px;
        border: 1px solid #bdbdbb;
        cursor: pointer;
    }

    .popup_that_shows_on_startup .tab > .left-side > label{
        margin: 5px auto 0;
        width: 70%;
        text-align: start;
    }

    .popup_that_shows_on_startup .tab > .left-side > select#categories, .popup_that_shows_on_startup .tab > .left-side > select#key_words{
        max-height: 75px;
    }

    .popup_that_shows_on_startup .tab > .left-side > button#login, .popup_that_shows_on_startup .tab > .left-side > button#register{
        border: solid 2px #000;
        font-weight: bold;
        border-radius: 50px;
        font-weight: bold;
        margin-top: 10px;
    }

    .popup_that_shows_on_startup .tab > .left-side > .navigation_buttons{
        width: 70%;
        margin: 10px auto 0;
        height: 40px;
        display: flex;
        justify-content: space-evenly;
    }

    #make_a_trip_popup .tab .trip_categories{
        padding: 0 10px;
    }

    #make_a_trip_popup .tab .trip_categories > span{
        width: fit-content;
        display: inline-block;
        margin: 10px;
    }

    #make_a_trip_popup .tab .trip_categories > span.active{
        border-color: #2c085d;
        background-color: #2c085d;
        color: #fff;
        transition: all 0.2s;
    }

    .popup_that_shows_on_startup .tab > .left-side > input{
        cursor: auto !important;
    }

    @media only screen and (max-width: 830px){
        .popup_that_shows_on_startup #make_a_trip_popup{
            width: 90%;
            margin-top: 150px;
            margin-left: 5%;
        }
    }

    /* make a trip popup end */

</style>

<div class="popup_that_shows_on_startup" dir="ltr">

    <div id="make_a_trip_popup" class="container hide" style="z-index: 999999;">
        <div dir="{{(app()->getLocale() === 'en') ? 'ltr' : 'rtl'}}">
            <h5>{{__('dashboard.ready_to_make_a_trip')}}</h5>
        </div>
        <form action="{{ route('front.action_selected_places') }}" method="POST" id="trip_form">
            <input type="hidden" name="type" id="type" value="guest">
            @csrf
            <div class="tabs row">
                <div  dir="{{(app()->getLocale() === 'en') ? 'ltr' : 'rtl'}}" class="main_tab col-12">
                    <div class="left-side">
                        <p class="description">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Nemo qui incidunt, quam id architecto, repellat vel fuga placeat distinctio quia magni praesentium reiciendis aspernatur eveniet iure? Omnis dicta eligendi tempore.
                        </p>
                        <button type="button" class="make_a_trip_button" onclick="makeATripNextTab(1)">{{__('dashboard.make_a_trip')}}</button>
                        </div>
                    <div class="right-side">
                        <img src="{{ asset('front_assets/imgs/popup_images/make_a_trip.png') }}" alt="fabulous trip image">
                    </div>
                </div>

                <div  dir="{{(app()->getLocale() === 'en') ? 'ltr' : 'rtl'}}" class="tab hide col-12">
                    <div class="left-side">
                        <!-- <h6>{{__('dashboard.select_date_place')}}</h6> -->
                        <label for="date">{{__('dashboard.select_date')}}</label>
                        <input type="date" name="date" placeholder="" id="date" required>
                        <label for="region_id">{{__('dashboard.select_region')}}</label>
                        <select name="region_id" id="region_id"  required>
                            <option value="">{{ __('dashboard.select_region') }}</option>
                            @foreach($regions as $region)
                            <option value="{{ $region->id }}" {{ old('region_id') == $region->id ? 'selected ': '' }}>{{ $region->name ?? '---' }}</option>
                            @endforeach
                        </select>
                        <label for="city_id">{{__('dashboard.select_city')}}</label>
                        <select name="city_id" id="city_id">
                            <option value="">{{ __('dashboard.select_city') }}</option>
                        </select>
                        <div class="navigation_buttons">
                            <button type="button" class="make_a_trip_button" onclick="makeATripNextTab(0)">{{__('dashboard.back')}}</button>
                            <button type="button" class="make_a_trip_button next" onclick="makeATripNextTab(2)" disabled>{{__('dashboard.next')}}</button>
                        </div>
                    </div>
                    <div class="right-side">
                        <img src="{{ asset('front_assets/imgs/popup_images/pick_city_and date.png') }}" alt="fabulous trip image">
                    </div>
                </div>
                <div  dir="{{(app()->getLocale() === 'en') ? 'ltr' : 'rtl'}}" class="tab hide col-12">
                    <div class="left-side">
                        <!-- <h6>{{__('dashboard.specify_trip')}}</h6> -->
                        <label for="season">{{__('dashboard.select_season')}}</label>
                        <select name="season" id="season" required>
                            @foreach($seasons as $item)
                            <option value="{{$item}}">{{$item}}</option>
                            @endforeach
                        </select>
                        <label for="price">{{__('dashboard.select_price')}}</label>
                        <select name="price" id="price" required>
                            @foreach($prices as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                        <label for="days">{{__('dashboard.days')}}</label>
                        <input type="number" name="days" placeholder="" id="days" required>
                        <label for="funny_days">{{__('dashboard.funny_days')}}</label>
                        <input type="number" name="funny_days" placeholder="" id="funny_days" required>
                        <div class="navigation_buttons">
                            <button type="button" class="make_a_trip_button" onclick="makeATripNextTab(1)">{{__('dashboard.back')}}</button>
                            <button type="button" class="make_a_trip_button next" onclick="makeATripNextTab(3)" disabled>{{__('dashboard.next')}}</button>
                        </div>
                    </div>
                    <div class="right-side">
                        <img src="{{ asset('front_assets/imgs/popup_images/pick_season_and days.png') }}" alt="fabulous trip image">
                    </div>
                </div>
                <div  dir="{{(app()->getLocale() === 'en') ? 'ltr' : 'rtl'}}" class="tab hide col-12">
                    <div class="left-side">
                        <!-- <h6>{{__('dashboard.select_many')}}</h6> -->
                        <label for="key_words">{{__('dashboard.select_trip_type')}}</label>
                        <select style="height: 150px;" name="key_words[]" id="example-getting-started" multiple>
                            @foreach($key_words as $item)
                            <option value="{{$item}}">{{$item}}</option>
                            @endforeach
                        </select>

                        <!-- <h6>{{__('dashboard.select_many')}}</h6> -->
                        <label for="categories">{{__('dashboard.advanced_options')}}</label>
                        <select style="height: 150px;" name="categories[]" id="categories" multiple>
                            @foreach($categories as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>

                        <div class="navigation_buttons">
                            <button type="button" class="make_a_trip_button" onclick="makeATripNextTab(2)">{{__('dashboard.back')}}</button>
                            <button type="button" class="make_a_trip_button next" onclick="makeATripNextTab(4)">{{__('dashboard.next')}}</button>
                        </div>
                    </div>
                    <div class="right-side">
                        <img src="{{ asset('front_assets/imgs/popup_images/categories.png') }}" alt="fabulous trip image">
                    </div>
                </div>
                <div  dir="{{(app()->getLocale() === 'en') ? 'ltr' : 'rtl'}}" class="tab hide col-12">
                    <div class="left-side">
                        @if(!auth()->check())

                    
                        <!-- <h6>{{__('dashboard.login')}}</h6> -->
                        <label>{{__('dashboard.email')}}</label>
                        <input type="email" name="email" placeholder="" required>
                        <label>{{__('dashboard.password')}}</label>
                        <input type="password" name="password" placeholder="" required>
                        <button type="button" id="login" disabled>{{__('dashboard.login')}}</button>
                        <button type="submit" id="as_a_guest" class="">{{__('dashboard.continueAsAguest')}}</button>

                        <a href="#" onclick="makeATripNextTab(5)">{{__('dashboard.register')}}</a>
                        @else
                        <button type="submit" class="">{{__('dashboard.continue')}}</button>
                        @endif

                        <div class="navigation_buttons">
                            <button type="button" class="make_a_trip_button" onclick="makeATripNextTab(3)">{{__('dashboard.back')}}</button>
                        </div>
                    </div>
                    <div class="right-side">
                        <img src="{{ asset('front_assets/imgs/popup_images/make_a_trip.png') }}" alt="fabulous trip image">
                    </div>
                </div>

                @if(!auth()->check())
                <div  dir="{{(app()->getLocale() === 'en') ? 'ltr' : 'rtl'}}" class="tab hide col-12">
                    <div class="left-side">
                        <!-- <h6>{{__('dashboard.register')}}</h6> -->
                        <label>{{__('dashboard.first_name')}}</label>
                        <input type="text" name="first_name" placeholder="" required>
                        <label>{{__('dashboard.last_name')}}</label>
                        <input type="text" name="last_name" placeholder="" required>
                        <label>{{__('dashboard.email')}}</label>
                        <input type="email" name="register_email" placeholder="" required>
                        <label>{{__('dashboard.password')}}</label>
                        <input type="password" name="register_password" placeholder="" required>
                        <button type="button" id="register" disabled>{{__('dashboard.register')}}</button>

                        <div class="navigation_buttons">
                            <button type="button" class="make_a_trip_button" onclick="makeATripNextTab(4)">{{__('dashboard.back')}}</button>
                        </div>
                    </div>
                    <div class="right-side">
                        <img src="{{ asset('front_assets/imgs/popup_images/make_a_trip.png') }}" alt="fabulous trip image">
                    </div>
                </div>
                @endif

            </div>
        </form>
    </div>
    <div id="popup_background"></div>
</div>

<!-- Initialize the plugin: -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#example-getting-started').multiselect();
    });
</script>