<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    /* make a trip popup start */

    .popup_that_shows_on_startup{
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        right: 0;
        z-index: 999;
        background-color: #000d;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .popup_that_shows_on_startup #make_a_trip_popup{
        z-index: 999999;
        display: flex;
        flex-direction: column;
        width: 800px;
        background-color: #fff;
        padding: 30px;
        border-radius: 10px;
        text-align: center;
        transition: all 0.5s;
        visibility: visible;
        opacity: 1;
        overflow: hidden;
    }

    .popup_that_shows_on_startup.hide{
        /* transition: all 0.5s;
        visibility: hidden;
        opacity: 0;
        z-index: -999; */
        display: none;
    }

    .popup_that_shows_on_startup #make_a_trip_popup h5 {
        margin-bottom: 20px;
        font-weight: bold;
        width: 60%;
    }

    .popup_that_shows_on_startup #make_a_trip_popup .tabs {
        flex-wrap: nowrap;
    }

    .popup_that_shows_on_startup #make_a_trip_popup .main_tab,
    .popup_that_shows_on_startup #make_a_trip_popup .tab {
        display: flex;
        flex-direction: row;
        justify-content: space-evenly;
    }

    .popup_that_shows_on_startup #make_a_trip_popup .main_tab>.left-side>p.description {
        width: 75%;
        margin: 20px auto;
    }

    .popup_that_shows_on_startup #make_a_trip_popup .main_tab>.left-side>button.make_a_trip_button {
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

    .popup_that_shows_on_startup .tabs .left-side {
        padding-bottom: 25px;
        width: 60%;
        display: flex;
        flex-direction: column;
        margin: auto;
    }

    .popup_that_shows_on_startup .tabs .right-side {
        width: 40%;
        text-align: start;
    }

    .popup_that_shows_on_startup .tabs .right-side img {
        width: 85%;
        object-fit: cover;
        height: 350px;
        min-height: 90%;
        border-radius: 50px;
    }

    .popup_that_shows_on_startup #make_a_trip_popup .main_tab>.left-side>button.make_a_trip_button:hover:not([disabled]),
    .popup_that_shows_on_startup #make_a_trip_popup .tab button.make_a_trip_button:hover:not([disabled]),
    .popup_that_shows_on_startup .tab>.left-side>button:hover:not([disabled]) {
        background-color: #2c085d;
        color: #fff;
    }

    .popup_that_shows_on_startup #make_a_trip_popup .tab button.make_a_trip_button {
        border: solid 2px #000;
        font-weight: bold;
        width: 100px;
        border-radius: 50px;
        font-weight: bold;
    }

    .popup_that_shows_on_startup .tab{
        display: flex;
        flex-direction: column;
        width: 100%;
    }

    .popup_that_shows_on_startup .tab.registerPage.hide {
        display: none !important;
    }

    .popup_that_shows_on_startup .tab>.left-side>*:not(h6):not(.trip_categories):not(a):not(.navigation_buttons):not(label):not(.timetable_container),
    #make_a_trip_popup .tab .trip_categories>span {
        width: 70% !important;
        margin: 0 auto 20px;
        height: 40px !important;
        border-radius: 10px;
        padding: 5px 10px !important;
        border: 1px solid #2c085d !important;
        cursor: pointer;
        background-color: hsl(231, 20%, 85%);
    }
    
    .popup_that_shows_on_startup .tab > .left-side > #as_a_guest{
        border: none !important;
        background-color: unset;
        color: #2c085d;
    }
    
    .popup_that_shows_on_startup .tab > .left-side > #as_a_guest:hover{
        color: #8d839b;
        text-decoration: underline;
    }

    .select2-container {
        z-index: 999999;
    }

    .popup_that_shows_on_startup .tab>.left-side>.select2.select2-container.select2-container--default.select2-container--below {
        height: fit-content !important;
    }

    /* .popup_that_shows_on_startup .tab > .left-side > input#date{
        background-color: #2c085d;
        color: #fff;
    } */

    input::-webkit-calendar-picker-indicator {
        display: none;
    }

    ::-webkit-calendar-picker-indicator {
        color: #fff;
        background-color: #fff;
        border-radius: 5px
    }

    ::-webkit-inner-spin-button {
        cursor: pointer;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100%;
        width: 30px;
    }

    .popup_that_shows_on_startup .tab>.left-side>label::after {
        content: '*';
        color: red;
        font-size: 20px;
        margin: 0 10px;
    }

    .popup_that_shows_on_startup .tab>.left-side>label.not_required::after {
        content: '' !important;
    }

    .popup_that_shows_on_startup .tab>.left-side>label {
        margin: 5px auto 0;
        width: 70%;
        text-align: start;
    }

    .popup_that_shows_on_startup .tab>.left-side>select#categories,
    .popup_that_shows_on_startup .tab>.left-side>select#key_words {
        max-height: 75px;
    }

    .popup_that_shows_on_startup .tab > .left-side > button#login, .popup_that_shows_on_startup .tab > .left-side > button#register{
        border-radius: 50px;
        font-weight: bold;
        margin-top: 10px;
        background-color: #2c085d;
        color: #fff;
    }
    
    .popup_that_shows_on_startup .tab > .left-side > button#login:hover, .popup_that_shows_on_startup .tab > .left-side > button#register:hover{
        background-color: #8d839b;
        color: #2c085d;
    }

    .popup_that_shows_on_startup .tab>.left-side>.navigation_buttons {
        width: 70%;
        margin: 10px auto 0;
        height: 40px;
        display: flex;
        justify-content: space-evenly;
    }

    #make_a_trip_popup .tab .trip_categories {
        padding: 0 10px;
    }

    #make_a_trip_popup .tab .trip_categories>span {
        width: fit-content;
        display: inline-block;
        margin: 10px;
    }

    #make_a_trip_popup .tab .trip_categories>span.active {
        border-color: #2c085d;
        background-color: #2c085d;
        color: #fff;
        transition: all 0.2s;
    }

    .popup_that_shows_on_startup .tab>.left-side>input {
        cursor: auto !important;
    }

    .popup_that_shows_on_startup .tab>.left-side>input#date {
        cursor: pointer !important;
    }

    /* select2 lib start */
    .select2-container--default .select2-selection--single,
    .select2-selection {
        background-color: #d1d3e0 !important;
        border: none !important;
    }

    .select2-search--inline {
        display: inline;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field,
    .select2-dropdown.select2-dropdown--above {
        border: 1px solid #2c085d;
        border-radius: 10px;
    }

    .select2-container--open .select2-dropdown--below,
    .select2-dropdown.select2-dropdown--above {
        z-index: 9999999;
        background-color: #d1d3e0;
        border-radius: 10px;
        border: solid 1px #2c085d;
    }

    .select2-container--default[dir="rtl"] .select2-selection--single .select2-selection__arrow {
        left: 15px;
        right: auto;
        top: 5px;
    }

    .popup_that_shows_on_startup .tab>.left-side>.select2 {
        border: none !important;
        padding: 0 !important;
        height: fit-content !important;
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        text-align: start;
        background-color: hsl(231, 20%, 85%);
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        padding-right: 5px;
        background-color: #2c085d;
        color: #fff;
        border: none;
        margin-top: 2px;
    }

    .select2-results__option {
        cursor: pointer;
    }

    .select2-results__option:hover {
        background-color: #e5e5e5;
    }

    .select2-container--default[dir="rtl"] .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
    }

    .select2-container--default[dir="rtl"] .select2-selection--multiple .select2-selection__choice__remove:hover {
        background: transparent;
    }

    /* select2 lib end */
    /* loader start */
    .popup_that_shows_on_startup #make_a_trip_popup .loader_infinity {
        position: absolute;
        left: 42%;
        top: 39%;
        width: 150px;
        transition: all 0.2s;
    }

    .popup_that_shows_on_startup #make_a_trip_popup .loader_infinity.hide {
        display: none;
    }

    /* loader end */
    /* timetable start */
    @import url('https://fonts.googleapis.com/css?family=Ubuntu:500&display=swap');

    .timetable_container {
        position: absolute;
        z-index: 9999999;
        left: 200px;
    }

    .timetable_container.hide {
        display: none;
    }

    .timetable_container .calendar {
        box-shadow: 0 0 10px #878787;
        padding: 1em;
        border-radius: 10px;
        display: grid;
        place-items: center;
        grid-template-columns: repeat(7, 1fr);
        grid-auto-rows: max-content;
        grid-auto-flow: row;
        color: black;
        background-color: hsl(231, 20%, 85%);
    }

    .timetable_container .cell {
        padding: 0.4em 0.8em;
        text-align: center;

        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .timetable_container .cell:not(.cell--unselectable) {
        cursor: pointer;
    }

    .timetable_container .cell:hover,
    .cell:focus {
        color: hsl(231, 20%, 85%);
        background-color: #2c085d;
        border-radius: 10px;
    }

    .timetable_container .cell:empty {
        width: 0;
        padding: 0;
    }

    .timetable_container .cell--unselectable {
        color: hsl(231, 20%, 50%);
    }

    .timetable_container .cell--unselectable:hover,
    .cell--unselectable:focus {
        color: hsl(231, 20%, 50%);
        background-color: transparent;
    }

    .timetable_container .date-text {
        padding: 1em 0.8em;
        grid-column: 1 / 5;
        justify-self: start;
        display: flex;
        align-items: center;

        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .timetable_container .button {
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* OTHER */

    .timetable_container {
        height: 200px;
        width: fit-content;


        font-family: 'Ubuntu', sans-serif;
    }

    /* timetable end */

    @media only screen and (max-width: 830px) {
        .popup_that_shows_on_startup #make_a_trip_popup {
            width: 90%;
            margin-top: 150px;
            margin-left: 5%;
        }
    }

    /* make a trip popup end */
</style>

<div class="popup_that_shows_on_startup hide" dir="ltr">
    <div id="make_a_trip_popup" class="container" style="z-index: 999999;">
        <div dir="{{ app()->getLocale() === 'en' ? 'ltr' : 'rtl' }}">
            <h5>{{ __('dashboard.ready_to_make_a_trip') }}</h5>
        </div>
        <form action="{{ route('front.action_selected_places') }}" method="POST" id="trip_form">
            <input type="hidden" name="type" id="type" value="guest">
            @csrf
            <img class="loader_infinity hide" src="{{ asset('front_assets/imgs/popup_images/Infinity_loader.gif') }}"
                alt="fabulous trip image">
            <div class="tabs row">
                <div dir="{{ app()->getLocale() === 'en' ? 'ltr' : 'rtl' }}" class="main_tab col-12">
                    <div class="left-side">
                        <p class="description">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Nemo qui incidunt, quam id
                            architecto, repellat vel fuga placeat distinctio quia magni praesentium reiciendis
                            aspernatur eveniet iure? Omnis dicta eligendi tempore.
                        </p>
                        <button type="button" class="make_a_trip_button"
                            onclick="makeATripNextTab(1)">{{ __('dashboard.make_a_trip') }}</button>
                    </div>
                    <div class="right-side">
                        <img src="{{ asset('front_assets/imgs/popup_images/trip_5.jpg') }}" alt="fabulous trip image">
                    </div>
                </div>

                <div dir="{{ app()->getLocale() === 'en' ? 'ltr' : 'rtl' }}" class="tab hide col-12">
                    <div class="left-side">
                        <label for="date">{{ __('dashboard.select_date') }}</label>
                        <div class="timetable_container hide">
                            <div id="calendar" class="calendar">
                                <span>Add something here</span>
                            </div>
                        </div>
                        <input type="date" name="date" placeholder="" id="date" onclick="openCalendar()"
                            required>

                        <label for="region_id">{{ __('dashboard.select_region') }}</label>
                        <select class="select2" name="region_id" id="region_id" required>
                            <option value="">{{ __('dashboard.select_region') }}</option>
                            @foreach ($regions as $region)
                                <option value="{{ $region->id }}"
                                    {{ old('region_id') == $region->id ? 'selected ' : '' }}>
                                    {{ $region->name ?? '---' }}</option>
                            @endforeach
                        </select>

                        <label for="city_id">{{ __('dashboard.select_city') }}</label>
                        <select class="select2" name="city_id" id="city_id">
                            <option value="">{{ __('dashboard.select_city') }}</option>
                        </select>
                        <div class="navigation_buttons">
                            <button type="button" class="make_a_trip_button"
                                onclick="makeATripNextTab(0)">{{ __('dashboard.back') }}</button>
                            <button type="button" class="make_a_trip_button next" onclick="makeATripNextTab(2)"
                                disabled>{{ __('dashboard.next') }}</button>
                        </div>
                    </div>
                    <div class="right-side">
                        <img src="{{ asset('front_assets/imgs/popup_images/trip_4.jpg') }}" alt="fabulous trip image">
                    </div>
                </div>
                <div dir="{{ app()->getLocale() === 'en' ? 'ltr' : 'rtl' }}" class="tab hide col-12">
                    <div class="left-side">

                        <label for="price">{{ __('dashboard.select_price') }}</label>
                        <select class="select2" name="price" id="price" required>
                            @foreach ($prices as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>

                        <label for="days">{{ __('dashboard.days') }}</label>
                        <input type="number" name="days" placeholder="" id="days" required>

                        <label for="funny_place_per_day">{{ __('dashboard.funny_place_per_day') }}</label>
                        <input type="number" name="funny_place_per_day" placeholder="" id="funny_place_per_day" required>

                        <div class="navigation_buttons">
                            <button type="button" class="make_a_trip_button"
                                onclick="makeATripNextTab(1)">{{ __('dashboard.back') }}</button>
                            <button type="button" class="make_a_trip_button next" onclick="makeATripNextTab(3)"
                                disabled>{{ __('dashboard.next') }}</button>
                        </div>
                    </div>
                    <div class="right-side">
                        <img src="{{ asset('front_assets/imgs/popup_images/trip-3.jpg') }}" alt="fabulous trip image">
                    </div>
                </div>
                <div dir="{{ app()->getLocale() === 'en' ? 'ltr' : 'rtl' }}" class="tab hide col-12">
                    <div class="left-side">
                        @if ($key_words && count($key_words) > 0)
                            <label for="key_words">{{ __('dashboard.select_trip_type') }}</label>
                            <select class="select2" style="height: 150px;" name="key_words[]"
                                id="example-getting-started" multiple>
                                @foreach ($key_words as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        @endif

                        @if ($categories && count($categories) > 0)
                            <label for="categories">{{ __('dashboard.advanced_options') }}</label>
                            <select class="select2" style="height: 150px;" name="categories[]" id="categories"
                                multiple>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        @endif

                        <div class="navigation_buttons">
                            <button type="button" class="make_a_trip_button"
                                onclick="makeATripNextTab(2)">{{ __('dashboard.back') }}</button>
                            <button type="button" class="make_a_trip_button next"
                                onclick="makeATripNextTab(4)">{{ __('dashboard.next') }}</button>
                        </div>
                    </div>
                    <div class="right-side">
                        <img src="{{ asset('front_assets/imgs/popup_images/trip_2.jpg') }}"
                            alt="fabulous trip image">
                    </div>
                </div>
                <div dir="{{ app()->getLocale() === 'en' ? 'ltr' : 'rtl' }}" class="tab hide col-12">
                    <div class="left-side">
                        @if (!auth()->check())
                            <label>{{ __('dashboard.email') }}</label>
                            <input type="email" name="email" placeholder="Email" required>
                            <label>{{ __('dashboard.password') }}</label>
                            <input type="password" name="password" placeholder="Password" required>
                            <button type="button" id="login" disabled>{{ __('dashboard.login') }}</button>
                            <button type="submit" id="as_a_guest"
                                class="">{{ __('dashboard.continueAsAguest') }}</button>

                            <a href="#" onclick="makeATripNextTab(5)">{{ __('dashboard.register') }}</a>
                        @else
                            <button type="submit" class="">{{ __('dashboard.continue') }}</button>
                        @endif

                        <div class="navigation_buttons">
                            <button type="button" class="make_a_trip_button"
                                onclick="makeATripNextTab(3)">{{ __('dashboard.back') }}</button>
                        </div>
                    </div>
                    <div class="right-side">
                        <img src="{{ asset('front_assets/imgs/popup_images/trip_1.jpg') }}"
                            alt="fabulous trip image">
                    </div>
                </div>

                @if (!auth()->check())
                    <div dir="{{ app()->getLocale() === 'en' ? 'ltr' : 'rtl' }}" class="tab hide col-12 registerPage">
                        <div class="left-side">
                            <label>{{ __('dashboard.first_name') }}</label>
                            <input type="text" name="first_name" placeholder="" required>
                            <label>{{ __('dashboard.last_name') }}</label>
                            <input type="text" name="last_name" placeholder="" required>
                            <label>{{ __('dashboard.email') }}</label>
                            <input type="email" name="register_email" placeholder="" required>
                            <label>{{ __('dashboard.password') }}</label>
                            <input type="password" name="register_password" placeholder="" required>
                            <button type="button" id="register" disabled>{{ __('dashboard.register') }}</button>

                            <div class="navigation_buttons">
                                <button type="button" class="make_a_trip_button"
                                    onclick="makeATripNextTab(4)">{{ __('dashboard.back') }}</button>
                            </div>

                        </div>
                        <div class="right-side">
                            <img src="{{ asset('front_assets/imgs/popup_images/trip_5.jpg') }}"
                                alt="fabulous trip image">
                        </div>
                    </div>
                @endif

            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>

<script>
    var calendarNode = document.querySelector("#calendar");

    var currDate = new Date();
    var currYear = currDate.getFullYear();
    var currMonth = currDate.getMonth() + 1;

    var selectedYear = currYear;
    var selectedMonth = currMonth;
    var selectedMonthName = getMonthName(selectedYear, selectedMonth);
    var selectedMonthDays = getDayCount(selectedYear, selectedMonth);

    renderDOM(selectedYear, selectedMonth);

    function getMonthName(year, month) {
        let selectedDate = new Date(year, month - 1, 1);
        return selectedDate.toLocaleString('default', {
            month: 'long'
        });
    }

    function getMonthText() {
        // if (selectedYear === currYear)
        //     return selectedMonthName;
        // else
        return selectedMonthName + ", " + selectedYear;
    }

    function getDayName(year, month, day) {
        let selectedDate = new Date(year, month - 1, day);
        return selectedDate.toLocaleDateString('en-US', {
            weekday: 'long'
        });
    }

    function getDayCount(year, month) {
        return 32 - new Date(year, month - 1, 32).getDate();
    }

    function getDaysArray() {
        let emptyFieldsCount = 0;
        let emptyFields = [];
        let days = [];

        switch (getDayName(selectedYear, selectedMonth, 1)) {
            case "Tuesday":
                emptyFieldsCount = 1;
                break;
            case "Wednesday":
                emptyFieldsCount = 2;
                break;
            case "Thursday":
                emptyFieldsCount = 3;
                break;
            case "Friday":
                emptyFieldsCount = 4;
                break;
            case "Saturday":
                emptyFieldsCount = 5;
                break;
            case "Sunday":
                emptyFieldsCount = 6;
                break;
        }

        emptyFields = Array(emptyFieldsCount).fill("");
        days = Array.from(Array(selectedMonthDays + 1).keys());
        days.splice(0, 1);

        return emptyFields.concat(days);
    }

    function renderDOM(year, month) {
        let newCalendarNode = document.createElement("div");
        newCalendarNode.id = "calendar";
        newCalendarNode.className = "calendar";

        let dateText = document.createElement("div");
        dateText.append(getMonthText());
        dateText.className = "date-text";
        newCalendarNode.append(dateText);

        let leftArrow = document.createElement("div");
        leftArrow.append("«");
        leftArrow.className = "button";
        leftArrow.addEventListener("click", goToPrevMonth);
        newCalendarNode.append(leftArrow);

        let curr = document.createElement("div");
        curr.append("📅");
        curr.className = "button";
        curr.addEventListener("click", goToCurrDate);
        newCalendarNode.append(curr);

        let rightArrow = document.createElement("div");
        rightArrow.append("»");
        rightArrow.className = "button";
        rightArrow.addEventListener("click", goToNextMonth);
        newCalendarNode.append(rightArrow);

        let dayNames = ["M", "T", "W", "T", "F", "S", "S"];

        dayNames.forEach((cellText) => {
            let cellNode = document.createElement("div");
            cellNode.className = "cell cell--unselectable";
            cellNode.append(cellText);
            newCalendarNode.append(cellNode);
        });

        let days = getDaysArray(year, month);

        days.forEach((cellText) => {
            let cellNode = document.createElement("div");
            cellNode.className = "cell";
            cellNode.append(cellText);
            newCalendarNode.append(cellNode);
        });

        calendarNode.replaceWith(newCalendarNode);
        calendarNode = document.querySelector("#calendar");
    }

    function goToPrevMonth() {
        selectedMonth--;
        if (selectedMonth === 0) {
            selectedMonth = 12;
            selectedYear--;
        }
        selectedMonthDays = getDayCount(selectedYear, selectedMonth);
        selectedMonthName = getMonthName(selectedYear, selectedMonth);

        renderDOM(selectedYear, selectedMonth);
    }

    function goToNextMonth() {
        selectedMonth++;
        if (selectedMonth === 13) {
            selectedMonth = 1;
            selectedYear++;
        }
        selectedMonthDays = getDayCount(selectedYear, selectedMonth);
        selectedMonthName = getMonthName(selectedYear, selectedMonth);

        renderDOM(selectedYear, selectedMonth);
    }

    function goToCurrDate() {
        selectedYear = currYear;
        selectedMonth = currMonth;

        selectedMonthDays = getDayCount(selectedYear, selectedMonth);
        selectedMonthName = getMonthName(selectedYear, selectedMonth);

        renderDOM(selectedYear, selectedMonth);
    }

    // open calendar function
    function openCalendar() {
        $('.timetable_container.hide').removeClass('hide')
    }
    // function to pick date from calendar
    $('.timetable_container').click(function(e) {
        if (e.target.className == 'cell') {
            selectedDay = parseInt(e.target.textContent)
            if ((selectedDay + '').length == 1) {
                selectedDay = '0' + selectedDay
            }
            if ((selectedMonth + '').length == 1) {
                selectedMonth = '0' + selectedMonth
            }
            var selectedDateByUser = (selectedYear + '-' + selectedMonth + '-' + selectedDay)
            // alert(selectedDateByUser)
            $('input#date').val(selectedDateByUser)
            $('.timetable_container').addClass('hide')
            $('.timetable_container').siblings('div.navigation_buttons').children('button.next').prop(
                'disabled', false);
        }
    })

    $(document).mouseup(function(e) {
        if ($(e.target).closest(".timetable_container").length ===
            0) {
            // $(".container").hide();
            // alert('outside')
            $('.timetable_container').addClass('hide')
        }
    })
</script>
<script>
    // required star function
    var iii = 0
    $(document).ready(() => {
        $('.left-side').each(function() {
            $(this).children('input, select').each(function() {
                console.log($(this).attr('name'))
                if ($(this).is('[required]')) {} else {
                    console.log('found one')
                    $(this).prev('label').addClass('not_required')
                }
            })
        })
    })
</script>
