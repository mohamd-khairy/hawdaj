@extends('layouts.dashboard.master')

@section('page_header')
<h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.places')</h5>
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item text-muted">
        <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
    </li>
    <li class="breadcrumb-item text-muted">
        <a href="javascript:;" class="text-muted">@lang('dashboard.setting')</a>
    </li>
    <li class="breadcrumb-item text-muted">
        <a href="javascript:;" class="text-muted">@lang('dashboard.data_entry')</a>
    </li>
    <li class="breadcrumb-item text-muted">
        <a href="{{url('dashboard/setting/places')}}" class="text-muted">@lang('dashboard.places')</a>
    </li>
    <li class="breadcrumb-item text-muted">
        <a href="javascript:;" class="text-muted">{{$title}}</a>
    </li>
</ul>

@endsection

@push('js')
<script src="{{ asset('front_assets/js/repeater.js') }}"></script>
    <script type="text/javascript">
        $('.repeater').repeater({
            repeaters: [{
                selector: '.inner-repeater'
            }]
        });
    </script>
<script>
    $(document).ready(function() {
        $('.insertion').select2({
            tags: true
        });
    });
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

<script>
    $("#region_id").on('change',
        function() {
            $("#ringle").css("display", "block");

            setTimeout(function() {
                $("#ringle").css("display", "none");
            }, 200);

            // $("#ringle").css('display','block').delay(1000).fadeOut();
        });
</script>

<script>
    $("#place_options").change(
        function() {
            var getSort = $("#place_options option:selected").val();

            if (getSort == 'link') {
                $('#link_related').css('display', 'block');
                $('.latlong_related').css('display', 'none');
                $('.map_related').css('display', 'none');


            } else if (getSort == 'latlong') {
                $('.latlong_related').css('display', 'block');
                $('#link_related').css('display', 'none');
                $('.map_related').css('display', 'none');

            } else {
                $('.map_related').css('display', 'block');
                $('.latlong_related').css('display', 'none');
                $('#link_related').css('display', 'none');
            }
        });
</script>
<script>
    // parameter when you first load the API. For example:
    var map
    var myLatLng
    function initMap() {
        map = new google.maps.Map(document.getElementById('googleMap'), {
            center: {lat: {{ old("latitude") ?? '24.774265' }}, lng: {{ old("longitude") ?? '46.738586' }}},
            zoom: 16,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        });

        var input = /** @type {!HTMLInputElement} */(
            document.getElementById('pac-input'));

        // var types = document.getElementById('type-selector');
        // map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
        // map.controls[google.maps.ControlPosition.TOP_CENTER].push(types);

        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);

        var infowindow = new google.maps.InfoWindow();

        myLatLng = {lat: {{ old("lat") ?? '24.774265' }}, lng: {{ old("long") ?? '46.738586' }}};

        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            draggable:true,
        });


        autocomplete.addListener('place_changed', function () {
            infowindow.close();
            marker.setVisible(false);
            var place = autocomplete.getPlace();

            if (!place.geometry) {
                // User entered the name of a Place that was not suggested and
                // pressed the Enter key, or the Place Details request failed.
                window.alert("No details available for input: '" + place.name + "'");
                return;
            }

            // If the place has a geometry, then present it on a map.
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);  // Why 17? Because it looks good.
            }
            marker.setIcon(/** @type {google.maps.Icon} */({
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(35, 35)
            }));
            marker.setPosition(place.geometry.location);
            marker.setVisible(true);
            var item_Lat = place.geometry.location.lat()
            var item_Lng = place.geometry.location.lng()
            var item_Location = place.formatted_address;


            //alert("Lat= "+item_Lat+"__Lang="+item_Lng+"__Location="+item_Location);
            $("#lat").val(item_Lat);
            $("#lng").val(item_Lng);
            $("#location").val(item_Location);

            google.maps.event.addListener(marker, 'dragend', function(evt){
                document.getElementById('current').innerHTML = '<p>Marker dropped: Current Lat: ' + evt.latLng.lat().toFixed(3) + ' Current Lng: ' + evt.latLng.lng().toFixed(3) + '</p>';
                item_Lat= evt.latLng.lat().toFixed(3);
                item_Lng=evt.latLng.lng().toFixed(3);
                item_Location = place.formatted_address;
                $("#lat").val(item_Lat);
                $("#lng").val(item_Lng);
                $("#pac-input").val(item_Location);

            });

            google.maps.event.addListener(marker, 'dragstart', function(evt){
                document.getElementById('current').innerHTML = '<p>Currently dragging marker...</p>';
            });
            var address = '';
            if (place.address_components) {
                address = [
                    (place.address_components[0] && place.address_components[0].short_name || ''),
                    (place.address_components[1] && place.address_components[1].short_name || ''),
                    (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
            }

            infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
            infowindow.open(map, marker);

           
        });

        // Sets a listener on a radio button to change the filter type on Places
        // Autocomplete.
        function setupClickListener(id, types) {
            var radioButton = document.getElementById(id);
            radioButton.addEventListener('click', function () {
                autocomplete.setTypes(types);
            });
        }

        setupClickListener('changetype-all', []);
        setupClickListener('changetype-address', ['address']);
        setupClickListener('changetype-establishment', ['establishment']);
        setupClickListener('changetype-geocode', ['geocode']);
        
    }
   
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDBxzlvlX_3nEJk0sdxyS9Y4MT-nw-kPsQ&libraries=places&callback=initMap" async defer></script>
@endpush

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-custom gutter-b ">
                    <div class="card-header">
                        <h3 class="card-title">{{$title}}</h3>
                        <div class="card-toolbar">
                            <div class="example-tools justify-content-center">
                            </div>
                        </div>
                    </div>
                    <form id="form" novalidate="novalidate" class="kt-form kt-form--label-right" method="post" action="{{ route('dashboard.places.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.title')</label>
                                        <span class="text-danger"> * </span>
                                        <div class="input-group">
                                            <input type="text" name="title" value="{{old('title' , '')}}" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.title') ">
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('title') ? $errors->first('title') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.description')</label>
                                        <span class="text-danger"> * </span>
                                        <input class=" form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" value="{{old('description') }}" placeholder="@lang('dashboard.enter') @lang('dashboard.description') ">
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->has('description') ? $errors->first('description') : '' }}</strong>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.categories')</label>
                                        <span class="text-danger"> * </span>
                                        <div class="input-group">
                                            <select name="categories[]" class="form-control select2" id="" multiple>
                                                @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{in_array($category->id, old('categories') ?? []) ? 'selected' : ''}}>
                                                    {{ $category->name}}
                                                </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('categories') ? $errors->first('categories') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.address_type')</label>
                                        <span class="text-danger"> * </span>
                                        <div class="input-group">
                                            <select id="place_options" class="form-control select2" name="address_type">
                                                <option value="">{{ __('dashboard.select_type') }}</option>
                                                <option value="link" {{ old('address_type') == 'link' ? 'selected ': '' }}>{{ __('dashboard.link') }}</option>
                                                <option value="map" {{ old('address_type') == 'map'? 'selected':""}}>{{ __('dashboard.map') }}</option>
                                                <option value="latlong" {{ old('address_type') == 'latlong'? 'selected':""}}>{{ __('dashboard.latlong') }}</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('address_type') ? $errors->first('address_type') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{--the hidden elements--}}

                                <div class="form-group validated col-md-12" id="link_related" style="display: none">
                                    <label>@lang('dashboard.link')</label>
                                    <span class="text-danger"> * </span>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="la la-exclamation-triangle flaticon-exclamation-1"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="link" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.link') " aria-describedby="basic-addon1" value="{{old('address')}}">
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->has('address') ? $errors->first('address') : '' }}</strong>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 latlong_related" style="display: none">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.latitude')</label>
                                        <span class="text-danger"> * </span>
                                        <div class="input-group">
                                            <input type="number" id="lat" name="lat" value="{{old("lat") ?? ''}}" class="form-control {{ $errors->has('lat') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.lat') " aria-describedby="basic-addon1">
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('lat') ? $errors->first('lat') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 latlong_related" style="display: none">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.longitude')</label>
                                        <span class="text-danger"> * </span>
                                        <div class="input-group">
                                            <input type="number" name="long" id="lng" value="{{old("long") ?? ''}}" class="form-control {{ $errors->has('long') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.long') " aria-describedby="basic-addon1">
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('long') ? $errors->first('long') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="col-12 map_related" style="display: none">
                                
                                    <div class="form-group ">
                                        <div class="input-group ">
                                            <input type="text" id="pac-input" class="form-control " name="address" placeholder="Search Box" aria-describedby="basic-addon1">
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('address') ? $errors->first('address') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="googleMap" style="width:100%;height:400px;"> </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.region')</label>
                                        <span class="text-danger"> * </span>
                                        <div class="input-group">
                                            <select name="region_id" id="region_id" class="form-control select2">
                                                <option value="">{{ __('dashboard.select_region') }}</option>
                                                @foreach($regions as $region)
                                                <option value="{{ $region->id }}" {{ old('region_id') == $region->id ? 'selected ': '' }}>{{ $region->name ?? '---' }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('region_id') ? $errors->first('region_id') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.city')</label>
                                        <span class="text-danger"> * </span>
                                        <div id="ringle" class="lds-dual-ring" style="display:none;"></div>

                                        <div class="input-group">
                                            <select name="city_id" id="city_id" class="form-control select2" required>
                                                <option value="">{{ __('dashboard.select_city') }}</option>
                                                @foreach($cities as $city)
                                                <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected ': '' }}>{{ $city->name ?? '---' }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('city_id') ? $errors->first('city_id') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.prices')</label>
                                        <span class="text-danger"> * </span>
                                        <div class="input-group">
                                            <select name="price_id" class="form-control select2" id="">
                                                <option value="">{{ __('dashboard.select_price') }}</option>
                                                @foreach($prices as $price)
                                                <option value="{{ $price->id }}" {{ old('price_id') == $price->id ? 'selected ': '' }}> {{ $price->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('price_id') ? $errors->first('price_id') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.seasons')</label>
                                        <span class="text-danger"> * </span>
                                        <div class="input-group">
                                            <select name="seasons[]" class="form-control select2" id="" multiple>
                                                <option value="all_year" {{ in_array('all_year' , old('seasons' , []) ?? []) ? 'selected ': '' }}> {{ __('dashboard.all_year') }}</option>
                                                <option value="spring" {{ in_array('spring' , old('seasons' , []) ?? []) ? 'selected ': '' }}> {{ __('dashboard.spring_season') }}</option>
                                                <option value="summer" {{ in_array('summer' , old('seasons' , []) ?? []) ? 'selected ': '' }}> {{ __('dashboard.summer_season') }}</option>
                                                <option value="fall" {{ in_array('fall' , old('seasons' , []) ?? []) ? 'selected ': '' }}> {{ __('dashboard.fall_season') }}</option>
                                                <option value="winter" {{ in_array('winter' , old('seasons' , []) ?? [])? 'selected ': '' }}> {{ __('dashboard.winter_season') }}</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('seasons') ? $errors->first('seasons') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-group row validated">
                                        <div class="col-md-10">
                                            <label>{{__('dashboard.image')}}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="flaticon2-image-file"></i>
                                                    </span>
                                                </div>
                                                <input required type="file" name="image" accept=".png , .jpg, .jpeg" class="form-control file {{ $errors->has('image') ? 'is-invalid' : '' }}" placeholder="{{__('dashboard.enter')}} {{__('dashboard.image')}}">
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('image') ? $errors->first('image') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 image">
                                            <div class="image_prev_form thumb-output">
                                                <img src="{{asset('dashboard_assets/media/blank.png')}}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="col-6">

                                    <div class="form-group validated">
                                        <label>@lang('dashboard.visited')</label>
                                        <span class="text-danger"> * </span>
                                        <div class="input-group">
                                            <select id="" class="form-control select2" name="visited">
                                                <option value="">{{ __('dashboard.select_type') }}</option>
                                                <option value="1" {{ old('visited') == '1' ? 'selected ': '' }}>{{ __('dashboard.yes') }}</option>
                                                <option value="0" {{ old('visited') == '0'? 'selected':""}}>{{ __('dashboard.no') }}</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('visited') ? $errors->first('visited') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                </div>


                                <div class="col-3">


                                    <span class="switch switch-outline switch-icon switch-success">
                                        <label style="margin:15px">@lang('dashboard.status') : </label>
                                        <label>
                                            <input type="checkbox" checked="checked" name="active" value="true" />
                                            <span></span>
                                        </label>
                                    </span>
                                </div>

                                <div class="col-3">

                                    <span class="switch switch-outline switch-icon switch-success">
                                        <label style="margin:15px">@lang('dashboard.featured') : </label>
                                        <label>
                                            <input type="checkbox" checked="checked" name="featured" value="true" />
                                            <span></span>
                                        </label>
                                    </span>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.key_words')</label>
                                        <div class="input-group">
                                            <select class="form-control insertion" name="key_words[]" multiple="multiple" >
                                                @if(isset($key_words) && count($key_words) > 0)
                                                    @foreach($key_words as $key_word)
                                                        <option value="{{$key_word}}">{{$key_word ?? ''}}</option>
                                                    @endforeach
                                                @endif
                                            </select>


                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('key_words') ? $errors->first('key_words') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">

                                    <div class="form-group">
                                        
                                        <div class="repeater col-12">
                                            <div data-repeater-list="new_key_words" class="col-12">
                                                <div data-repeater-item class="row col-12">
                                                    <div class="form-group col-2 ">
                                                        <label >{{  __("dashboard.key_words") }}</label>
                                                    </div>
                                                    <div class="col-4">
                                                        <input class="form-control" type="text" name="new_key_words"  value="">

                                                    </div>
                                                    <a class="col-2" href="javascript:void(0);"
                                                        data-repeater-delete>Remove
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col p-0">
                                                <button id="repeater-add" class="btn btn-primary" data-repeater-create
                                                    type="button"><i class="os-icon os-icon-folder-plus"></i>
                                                    Add
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-primary">@lang('dashboard.submit')</button>
                                <button type="reset" class="btn btn-secondary">@lang('dashboard.cancel')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection