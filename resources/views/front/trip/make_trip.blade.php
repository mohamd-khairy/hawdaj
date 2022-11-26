<div class="popup_that_shows_on_startup" dir="{{(app()->getLocale() === 'en') ? 'ltr' : 'rtl'}}">

    <div id="make_a_trip_popup" class="container hide" style="z-index: 999999;">
        <h5>{{__('dashboard.ready_to_make_a_trip')}}</h5>
        <form action="{{ route('front.action_selected_places') }}" method="POST" id="trip_form">
            <input type="hidden" name="type" id="type" value="guest">
            @csrf
            <div class="tabs row">
                <div class="main_tab col-12">
                    <p class="description">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Nemo qui incidunt, quam id architecto, repellat vel fuga placeat distinctio quia magni praesentium reiciendis aspernatur eveniet iure? Omnis dicta eligendi tempore.
                    </p>
                    <button type="button" class="make_a_trip_button" onclick="makeATripNextTab(1)">{{__('dashboard.make_a_trip')}}</button>
                </div>

                <div class="tab hide col-12">
                    <h6>Select Date and Place</h6>
                    <input type="date" name="date" placeholder="date" id="date" required>
                    <select name="region_id" id="region_id">
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
                        <button type="button" class="make_a_trip_button" onclick="makeATripNextTab(2)">{{__('dashboard.next')}}</button>
                    </div>
                </div>
                <div class="tab hide col-12">
                    <h6>Specify Your Desired Trip</h6>
                    <select name="season" id="season">
                        @foreach($seasons as $item)
                        <option value="{{$item}}">{{$item}}</option>
                        @endforeach
                    </select>

                    <select name="price" id="price">
                        @foreach($prices as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                    <input type="number" name="days" placeholder="days" id="days" required>
                    <input type="number" name="funny_days" placeholder="funny days" id="funny_days" required>
                    <div class="navigation_buttons">
                        <button type="button" class="make_a_trip_button" onclick="makeATripNextTab(1)">{{__('dashboard.back')}}</button>
                        <button type="button" class="make_a_trip_button" onclick="makeATripNextTab(3)">{{__('dashboard.next')}}</button>
                    </div>
                </div>
                <div class="tab hide col-12">

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
                        <button type="button" class="make_a_trip_button" onclick="makeATripNextTab(4)">{{__('dashboard.next')}}</button>
                    </div>
                </div>
                <div class="tab hide col-12">
                    @if(!auth()->check())

                    <button type="submit" class="">{{__('dashboard.continueAsAguest')}}</button>

                    <h6>Log In</h6>
                    <input type="email" name="email" placeholder="Email">
                    <input type="password" name="password" placeholder="Password">
                    <button type="button" id="login">{{__('dashboard.login')}}</button>

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
                    <input type="text" name="first_name" placeholder="First Name">
                    <input type="text" name="last_name" placeholder="Last Name">
                    <input type="email" name="register_email" placeholder="Email">
                    <input type="password" name="register_password" placeholder="Password">
                    <button type="button" id="register">{{__('dashboard.register')}}</button>

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