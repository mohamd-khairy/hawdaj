<style>
    /* make a trip popup start */

    .popup_that_shows_on_startup #make_a_trip_popup{
        position: fixed;
        top: 0;
        left: 0;
        width: 40%;
        /* height: calc(100% - 100px); */
        background-color: #fff;
        z-index: 4;
        margin-top: 100px;
        margin-left: 30%;
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

    .popup_that_shows_on_startup #make_a_trip_popup > h5{
        margin-bottom: 20px;
        font-weight: bold;
    }

    .popup_that_shows_on_startup #make_a_trip_popup .tabs{
        flex-wrap: nowrap;
    }

    .popup_that_shows_on_startup #make_a_trip_popup .tabs > div{
        padding-bottom: 25px;
    }

    .popup_that_shows_on_startup #make_a_trip_popup .main_tab, .popup_that_shows_on_startup #make_a_trip_popup .tab{
        display: flex;
        flex-direction: column;
        justify-content: space-evenly;
    }

    .popup_that_shows_on_startup #make_a_trip_popup .main_tab > p.description{
        margin: 20px;
    }

    .popup_that_shows_on_startup #make_a_trip_popup .main_tab > button.make_a_trip_button{
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

    .popup_that_shows_on_startup #make_a_trip_popup .main_tab > button.make_a_trip_button:hover:not([disabled]),
    .popup_that_shows_on_startup #make_a_trip_popup .tab button.make_a_trip_button:hover:not([disabled]),
    .popup_that_shows_on_startup .tab > button:hover:not([disabled]){
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

    .popup_that_shows_on_startup .tab > *:not(h6):not(.trip_categories):not(a):not(.navigation_buttons), #make_a_trip_popup .tab .trip_categories > span{
        width: 70%;
        margin: 5px auto;
        height: 40px;
        border-radius: 10px;
        padding: 5px 10px;
        border: 1px solid #bdbdbb;
        cursor: pointer;
    }

    .popup_that_shows_on_startup .tab > select#categories, .popup_that_shows_on_startup .tab > select#key_words{
        max-height: 75px;
    }

    .popup_that_shows_on_startup .tab > button#login, .popup_that_shows_on_startup .tab > button#register{
        border: solid 2px #000;
        font-weight: bold;
        border-radius: 50px;
        font-weight: bold;
    }

    .popup_that_shows_on_startup .tab > .navigation_buttons{
        width: 70%;
        margin: 5px auto;
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

    .popup_that_shows_on_startup .tab > input{
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
        <h5>{{__('dashboard.ready_to_make_a_trip')}}</h5>
        <form action="{{ route('front.action_selected_places') }}" method="POST" id="trip_form">
            <input type="hidden" name="type" id="type" value="guest">
            @csrf
            <div class="tabs row">
                <div  dir="{{(app()->getLocale() === 'en') ? 'ltr' : 'rtl'}}" class="main_tab col-12">
                    <p class="description">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Nemo qui incidunt, quam id architecto, repellat vel fuga placeat distinctio quia magni praesentium reiciendis aspernatur eveniet iure? Omnis dicta eligendi tempore.
                    </p>
                    <button type="button" class="make_a_trip_button" onclick="makeATripNextTab(1)">{{__('dashboard.make_a_trip')}}</button>
                </div>

                <div  dir="{{(app()->getLocale() === 'en') ? 'ltr' : 'rtl'}}" class="tab hide col-12">
                    <h6>Select Date and Place</h6>
                    <input type="date" name="date" placeholder="date" id="date" required>
                    <select name="region_id" id="region_id"  required>
                        <option value="">{{ __('dashboard.select_region') }}</option>
                        @foreach($regions as $region)
                        <option value="{{ $region->id }}" {{ old('region_id') == $region->id ? 'selected ': '' }}>{{ $region->name ?? '---' }}</option>
                        @endforeach
                    </select>
                    <select name="city_id" id="city_id">
                        <option value="">{{ __('dashboard.select_city') }}</option>
                    </select>
                    <div class="navigation_buttons">
                        <button type="button" class="make_a_trip_button" onclick="makeATripNextTab(0)">{{__('dashboard.back')}}</button>
                        <button type="button" class="make_a_trip_button next" onclick="makeATripNextTab(2)" disabled>{{__('dashboard.next')}}</button>
                    </div>
                </div>
                <div  dir="{{(app()->getLocale() === 'en') ? 'ltr' : 'rtl'}}" class="tab hide col-12">
                    <h6>Specify Your Desired Trip</h6>
                    <select name="season" id="season" required>
                        @foreach($seasons as $item)
                        <option value="{{$item}}">{{$item}}</option>
                        @endforeach
                    </select>

                    <select name="price" id="price" required>
                        @foreach($prices as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                    <input type="number" name="days" placeholder="days" id="days" required>
                    <input type="number" name="funny_days" placeholder="funny days" id="funny_days" required>
                    <div class="navigation_buttons">
                        <button type="button" class="make_a_trip_button" onclick="makeATripNextTab(1)">{{__('dashboard.back')}}</button>
                        <button type="button" class="make_a_trip_button next" onclick="makeATripNextTab(3)" disabled>{{__('dashboard.next')}}</button>
                    </div>
                </div>
                <div  dir="{{(app()->getLocale() === 'en') ? 'ltr' : 'rtl'}}" class="tab hide col-12">

                    <h6>Select as Many as You Like</h6>

                    <select style="height: 150px;" name="key_words[]" id="key_words" multiple>
                        @foreach($key_words as $item)
                        <option value="{{$item}}">{{$item}}</option>
                        @endforeach
                    </select>

                    <h6>Select as Many as You Like</h6>

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
                <div  dir="{{(app()->getLocale() === 'en') ? 'ltr' : 'rtl'}}" class="tab hide col-12">
                    @if(!auth()->check())

                    
                    <h6>Log In</h6>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
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

                @if(!auth()->check())
                <div class="tab hide col-12">
                    <h6>register </h6>
                    <input type="text" name="first_name" placeholder="First Name" required>
                    <input type="text" name="last_name" placeholder="Last Name" required>
                    <input type="email" name="register_email" placeholder="Email" required>
                    <input type="password" name="register_password" placeholder="Password" required>
                    <button type="button" id="register" disabled>{{__('dashboard.register')}}</button>

                    <div class="navigation_buttons">
                        <button type="button" class="make_a_trip_button" onclick="makeATripNextTab(4)">{{__('dashboard.back')}}</button>
                    </div>
                </div>
                @endif

            </div>
        </form>
    </div>
    <div id="popup_background"></div>
</div>