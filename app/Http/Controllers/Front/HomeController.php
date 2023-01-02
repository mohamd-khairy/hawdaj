<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Caravan;
use App\Models\Category;
use App\Models\CategoryOfStore;
use App\Models\CategoryOfZad;
use App\Models\City;
use App\Models\Gallery;
use App\Models\Opinion;
use App\Models\Place;
use App\Models\Price;
use App\Models\Rate;
use App\Models\Region;
use App\Models\Setting;
use App\Models\Store;
use App\Models\Swalef;
use App\Models\Trip;
use App\Models\User;
use App\Models\ZadElgadel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function save_trip(Request $request)
    {
        $data = $request->except('_token');
        $data['user_id'] = auth()->user()->id;
        if (!isset($request->name) && !$request->name) {
            $data['name'] = request('date');
        }

        Trip::firstOrCreate($data);

        return redirect()->route('front.my_trips');
    }

    public function my_trips()
    {
        $trips = Trip::where('user_id', auth()->user()->id)->get();
        $places = [];
        return view('front.trip.my_trips', compact('trips', 'places'));
    }

    public function logout()
    {
        auth()->logout();
        return redirect('/ar');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:users,email',
            'password' => 'required',
        ]);
        $user = auth()->attempt($request->only('email', 'password'));
        return redirect('/ar');
    }

    public function register(Request $request)
    {
        $request->validate([
            'register_email' => 'required',
            'register_password' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        $user = User::firstOrCreate([
            'email' => $request->register_email
        ], [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->register_email,
            'photo' => "assets/media/avatars/150-2.jpg",
            'password' => Hash::make($request->register_password),
        ]);
        if ($user) {
            auth()->login($user);
        }
        return redirect('/ar');
    }

    public function action_selected_places(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'days' => 'required',
            'funny_place_per_day' => 'required'
        ]);
        // return $request->all();
        $places = Place::inRandomOrder(); //->take(10);

        if (request('price')) {
            $places = $places->where('price_id', request('price'));
        }

        $month = (int)date('m', strtotime($request->date));
        if ($month >= 3 && $month <= 5) {
            $season = 'موسم الربيع';
            $places = $places->where('seasons', 'like', '%' . $season . '%');
        } elseif ($month >= 6 && $month <= 8) {
            $season = 'موسم الصيف';
            $places = $places->where('seasons', 'like', '%' . $season . '%');
        } elseif ($month >= 9 && $month <= 11) {
            $season = 'موسم الخريف';
            $places = $places->where('seasons', 'like', '%' . $season . '%');
        } elseif ($month >= 12 || $month <= 2) {
            $season = 'موسم الشتاء';
            $places = $places->where('seasons', 'like', '%' . $season . '%');
        }

        $places = $places->when($request->region_id, function ($qq) use ($request) {
            return $qq->where('region_id', $request->region_id);
        });

        $places = $places->when($request->city_id, function ($qqq) use ($request) {
            return $qqq->where('city_id', $request->city_id);
        });

        if (request('key_words', [])) {
            $places = $places->where(function ($q) {
                foreach (request('key_words', []) as $key => $value) {
                    $q->orWhereJsonContains('key_words', $value);
                }
            });
        }

        if (request('categories', [])) {
            $places = $places->where(function ($q) {
                foreach (request('categories', []) as $key => $value) {
                    $q->orWhereJsonContains('categories', $value);
                }
            });
        }

        $places = $places->get(); //->take(request('funny_place_per_day') * request('days'))

        $need_count = request('funny_place_per_day') * request('days');

        if ($places->count() > $need_count) {
            $places = $places->take($need_count);
        } else {
            $need = $need_count - $places->count();
            $new_places = Place::inRandomOrder()->where(function ($q) use ($season, $request) {
                $q->where('seasons', 'like', '%' . $season . '%')
                    ->orWhere('region_id', $request->region_id);
            })->take($need)->get();

            $places = array_merge($places->toArray(), $new_places->toArray());
        }

        $data = [];
        $items = [];
        $i = 0;
        foreach ($places as $key => $value) {
            if (count($data[$i] ?? []) < request('funny_place_per_day')) {
                $data[$i][] = $value;
                // $items[$i][] = $value['id'];
            } else {
                $i = $i + 1;
                $data[$i][] = $value;
                // $items[$i][] = $value['id'];
            }
            $items[] = $value['id'];
        }

        if ($request->type == 'register' && $request->first_name && $request->last_name && $request->register_email && $request->register_password) {
            $user = User::firstOrCreate([
                'email' => $request->register_email
            ], [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->register_email,
                'photo' => "assets/media/avatars/150-2.jpg",
                'password' => Hash::make($request->register_password),
            ]);

            auth()->login($user);
        } elseif ($request->type == 'login' && $request->email && $request->password) {
            $user = auth()->attempt($request->only('email', 'password'));
        }

        $user = auth()->user();

        if ($user) {
            if (!Session::get($user->email)) {
                Session::put($user->email, 'trip');
            }
        }

        $places = $data;
        $days = request('days');
        $funny_place_per_day = request('funny_place_per_day');
        $date = request('date');
        return view('front.trip.selected_places', compact('places', 'days', 'funny_place_per_day', 'items', 'date'));
    }

    public function view_trip($id)
    {
        $trip = Trip::findOrFail($id);
        $need_count = $trip->item_per_day * $trip->days;
        $places = Place::whereIn('id', json_decode($trip->items, true)); //->take(10);
        $places = $places->take($need_count)->get();

        $data = [];
        $i = 0;
        foreach ($places as $key => $value) {
            if (count($data[$i] ?? []) < $trip->item_per_day) {
                $data[$i][] = $value;
            } else {
                $i = $i + 1;
                $data[$i][] = $value;
            }
        }
        $places = $data;
        $date = $trip->date;

        return view('front.trip.selected_places', compact('places', 'trip', 'date'));
    }


    public function index()
    {
        $caravans = Caravan::get();
        $swalefs = Swalef::where('active', 1)->get();
        $zad_elgadels = ZadElgadel::where('active', 1)->take(3)->get();
        $stores_data =  Store::where('active', 1)->take(5)->get();
        $places_data = Place::where('featured', 1)->where('active', 1)->take(5)->get();
        $place_categories = Category::whereNull('parent_id')->get();

        return view('front.index', [
            'title' => __('dashboard.show_title', ['title' => __('dashboard.home')]),
        ], compact('caravans', 'place_categories', 'stores_data', 'places_data', 'swalefs', 'zad_elgadels'));
    }

    public function searchPlaces(Request $request)
    {
        if (!$request->search) {
            return [];
        }

        $result_places = Place::with('city', 'region')->where(function ($q) use ($request) {
            $q->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        })->where('active', 1)->take(10)->get();


        $result_stores = Store::with('city', 'region')->where(function ($q) use ($request) {
            $q->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        })->where('active', 1)->take(10)->get();

        $result_zads = ZadElgadel::with('city', 'region')->where(function ($q) use ($request) {
            $q->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        })->where('active', 1)->take(10)->get();


        $places = array_merge(($result_places  ? $result_places->toArray() : []), ($result_stores ? $result_stores->toArray() : []), ($result_zads ? $result_zads->toArray() : []));

        return $places ?? [];
    }

    public function Places(Request $request)
    {
        $categories = Category::whereNull('parent_id')->get();
        $prices = Price::where('show', 1)->get();
        $places = Place::with('ratings', 'galleries', 'city', 'region')->where('active', 1);

        if (request('search')) {
            $places = $places->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if (request('category_id')) {
            $x = '[' . request('category_id') . ']';
            $places = $places->whereRaw("JSON_CONTAINS(categories, '" . $x . "' )");
        }

        if (request('sub_category_id')) {
            $x = '[' . request('sub_category_id') . ']';
            $places = $places->whereRaw("JSON_CONTAINS(categories, '" . $x . "' )");
        }

        if (request('lat') && request('lng') && request('x') && request('y')) {
            $latitude = request('lat');
            $longitude = request('lng');
            $x = request('x');
            $y = request('y');

            $upper_latitude = $latitude + ($x + 2); //Change .50 to small values
            $lower_latitude = $latitude - ($x + 1); //Change .50 to small values
            $upper_longitude = $longitude + ($y + 2); //Change .50 to small values
            $lower_longitude = $longitude - ($y + 1); //Change .50 to small values

            $places = $places
                ->whereBetween('lat', [$lower_latitude, $upper_latitude])
                ->whereBetween('long', [$lower_longitude, $upper_longitude]);
        }

        if (request('price_id')) {
            $places = $places->where('price_id', request('price_id'));
        }

        if (request('temperature')) {
            $places = $places->where('temperature', request('temperature'));
        }

        $places_data = Place::with('ratings', 'city', 'region')->select('id', 'title', 'lat', 'long', 'image', 'city_id', 'region_id')
            ->whereNotNull('lat')->whereNotNull('long')->whereIn('id', $places->pluck('id'))->paginate(20);
        $places_data_for_map =  $places_data->toArray()['data'];
        $places = $places->paginate(20);

        return view('front.place.all', compact('places', 'categories', 'prices', 'places_data_for_map'));
    }

    public function PlaceDetails($id)
    {
        $place = Place::with('ratings', 'galleries', 'city', 'region')->where('active', 1)->findOrFail($id);
        $place_for_map = Place::with('city', 'region')->select('id', 'title', 'lat', 'long', 'image', 'city_id', 'region_id')->where('active', 1)->findOrFail($id);
        $inside_map = Place::select('id', 'lat', 'long')->where('id', '!=', $place->id)->where('active', 1)->get()->map(function ($i) use ($place) {
            if (round(distance($place['lat'], $place['long'], $i['lat'], $i['long'], "M"), 1) <= $place['distance'] ?? 0) {
                return $i['id'];
            }
        });
        $all_related_items = array_unique(array_merge(array_filter($inside_map->toArray()) ?? [], $place->related_places ?? []));

        $best_Places = $place ? Place::with('ratings', 'galleries', 'city', 'region')->whereIn('id', $all_related_items ?? [])->where('active', 1)->take(20)->get() : null;
        $best_stores = $place ? store::with('ratings', 'galleries', 'city', 'region')->whereIn('id', $place->near_stores ?? [])->where('active', 1)->take(20)->get() : null;
        if (!in_array($id, session()->get('ids', []))) {
            $ids = session()->get('ids', []);
            $ids[] = $id;
            session()->put('ids', $ids);
            $place->update(['views_num' => $place->views_num + 1]);
        }
        return view('front.place.details', compact('place', 'best_Places', 'best_stores', 'place_for_map'));
    }

    public function ratePlaces(Request $request)
    {
        $data = $request->all();
        return Rate::create($data);
    }

    public function getSubCategory()
    {
        return Category::where('parent_id', request('parent_id'))->get();
    }

    public function uploadMessage(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'required',
        ]);

        $request_data = $request->all();

        $data = Opinion::create($request_data);
        if ($request->ajax()) {
            session()->put('success', 'تم الارسال بنجاح');
            return $data;
        }
        return redirect()->route('front.index')->with(['success' => 'تم الارسال بنجاح']);
    }

    public function stores(Request $request)
    {
        $categories = CategoryOfStore::whereNull('parent_id')->get();
        $stores = Store::with('ratings', 'galleries', 'city', 'region')->where('active', 1);

        if (request('search')) {
            $stores = $stores->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if (request('category_id')) {
            $x = '["' . request('category_id') . '"]';
            $stores = $stores->whereRaw("JSON_CONTAINS(categories, '" . $x . "' )");
        }

        if (request('sub_category_id')) {
            $x = '["' . request('sub_category_id') . '"]';
            $stores = $stores->whereRaw("JSON_CONTAINS(categories, '" . $x . "' )");
        }

        if (request('lat') && request('lng') && request('x') && request('y')) {
            $latitude = request('lat');
            $longitude = request('lng');
            $x = request('x');
            $y = request('y');

            $upper_latitude = $latitude + ($x + 2); //Change .50 to small values
            $lower_latitude = $latitude - ($x + 1); //Change .50 to small values
            $upper_longitude = $longitude + ($y + 2); //Change .50 to small values
            $lower_longitude = $longitude - ($y + 1); //Change .50 to small values

            $stores = $stores
                ->whereBetween('lat', [$lower_latitude, $upper_latitude])
                ->whereBetween('long', [$lower_longitude, $upper_longitude]);
        }

        if (request('address_type')) {
            $stores = $stores->where('address_type', request('address_type'));
        }

        $stores = $stores->paginate(20);

        return view('front.store.all', compact('stores', 'categories'));
    }

    public function storeDetails($id)
    {
        $store = Store::with('ratings', 'galleries', 'city', 'region')->findOrFail($id);
        $best_stores = $store ? Store::with('ratings', 'galleries', 'city', 'region')->whereIn('id', $store->related_stores ?? [])->get() : null;
        $best_Places = $store ? Place::with('ratings', 'galleries', 'city', 'region')->whereIn('id', $store->near_places ?? [])->where('active', 1)->get() : null;
        if (!in_array($id, session()->get('ids', []))) {
            $ids = session()->get('ids', []);
            $ids[] = $id;
            session()->put('ids', $ids);
            $store->update(['views_num' => $store->views_num + 1]);
        }
        return view('front.store.details', compact('store', 'best_stores', 'best_Places'));
    }

    public function zads(Request $request)
    {
        $categories = CategoryOfZad::whereNull('parent_id')->get();
        $stores = ZadElgadel::with('ratings', 'galleries', 'city', 'region')->where('active', 1);

        if (request('search')) {
            $stores = $stores->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if (request('category_id')) {
            $x = '["' . request('category_id') . '"]';
            $stores = $stores->whereRaw("JSON_CONTAINS(categories, '" . $x . "' )");
        }

        if (request('sub_category_id')) {
            $x = '["' . request('sub_category_id') . '"]';
            $stores = $stores->whereRaw("JSON_CONTAINS(categories, '" . $x . "' )");
        }

        if (request('lat') && request('lng') && request('x') && request('y')) {
            $latitude = request('lat');
            $longitude = request('lng');
            $x = request('x');
            $y = request('y');

            $upper_latitude = $latitude + ($x + 2); //Change .50 to small values
            $lower_latitude = $latitude - ($x + 1); //Change .50 to small values
            $upper_longitude = $longitude + ($y + 2); //Change .50 to small values
            $lower_longitude = $longitude - ($y + 1); //Change .50 to small values

            $stores = $stores
                ->whereBetween('lat', [$lower_latitude, $upper_latitude])
                ->whereBetween('long', [$lower_longitude, $upper_longitude]);
        }

        if (request('address_type')) {
            $stores = $stores->where('address_type', request('address_type'));
        }

        $stores = $stores->paginate(20);
        $zad = 1;
        return view('front.store.all', compact('stores', 'categories', 'zad'));
    }

    public function zadDetails($id)
    {
        $store = ZadElgadel::with('ratings', 'galleries', 'city', 'region')->findOrFail($id);
        $best_stores = $store ? Store::with('ratings', 'galleries', 'city', 'region')->whereIn('id', $store->related_stores ?? [])->get() : null;
        $best_Places = $store ? Place::with('ratings', 'galleries', 'city', 'region')->whereIn('id', $store->near_places ?? [])->where('active', 1)->get() : null;
        if (!in_array($id, session()->get('ids', []))) {
            $ids = session()->get('ids', []);
            $ids[] = $id;
            session()->put('ids', $ids);
            $store->update(['views_num' => $store->views_num + 1]);
        }
        $zad = 1;
        return view('front.zad.details', compact('store', 'best_stores', 'best_Places', 'zad'));
    }


    public function getFullMap()
    {
        $size = request('size', 10);

        $all_places = Place::with('city', 'region')->select('id', 'title', 'lat', 'long', 'image', 'city_id', 'region_id', 'views_num')->where('active', 1)->whereNotNull('lat')->whereNotNull('long')->take($size)->get();

        $all_stores = Store::with('city', 'region')->select('id', 'title', 'lat', 'long', 'image', 'views_num')->where('active', 1)->whereNotNull('lat')->whereNotNull('long')->take($size)->get();

        $all_zad_elgadels = ZadElgadel::with('city', 'region')->select('id', 'title', 'lat', 'long', 'image', 'views_num')->where('active', 1)->whereNotNull('lat')->whereNotNull('long')->take($size)->get();

        $places = array_merge(($all_places  ? $all_places->toArray() : []), ($all_stores ? $all_stores->toArray() : []), ($all_zad_elgadels ? $all_zad_elgadels->toArray() : []));

        $pupular_places = Place::with('city', 'region', 'ratings')->where('active', 1)->whereNotNull('lat')->whereNotNull('long')->orderBy('views_num', 'desc')->take(10)->get();

        $pupular_stores = Store::with('city', 'region', 'ratings')->where('active', 1)->whereNotNull('lat')->whereNotNull('long')->orderBy('views_num', 'desc')->take(10)->get();

        $pupular_zad_elgadelss = ZadElgadel::with('city', 'region', 'ratings')->where('active', 1)->whereNotNull('lat')->whereNotNull('long')->orderBy('views_num', 'desc')->take(10)->get();

        $map_most_pupular = array_merge(($pupular_places  ? $pupular_places->toArray() : []), ($pupular_stores ? $pupular_stores->toArray() : []), ($pupular_zad_elgadelss ? $pupular_zad_elgadelss->toArray() : []));

        // $places = json_decode(json_encode((object) $map_places), FALSE);

        $map_most_pupular_places = json_decode(json_encode((object) $map_most_pupular), FALSE);

        return view('front.place.map', compact('places', 'map_most_pupular_places'));
    }

    public function getFullMapData()
    {
        $size = request('size', 10);

        $all_places = Place::with('city', 'region')->select('id', 'title', 'lat', 'long', 'image', 'city_id', 'region_id', 'views_num')->where('active', 1)->whereNotNull('lat')->whereNotNull('long')->take($size)->get();

        $all_stores = Store::with('city', 'region')->select('id', 'title', 'lat', 'long', 'image', 'views_num')->where('active', 1)->whereNotNull('lat')->whereNotNull('long')->take($size)->get();

        $all_zad_elgadels = ZadElgadel::with('city', 'region')->select('id', 'title', 'lat', 'long', 'image', 'views_num')->where('active', 1)->whereNotNull('lat')->whereNotNull('long')->take($size)->get();

        $places = array_merge(($all_places  ? $all_places->toArray() : []), ($all_stores ? $all_stores->toArray() : []), ($all_zad_elgadels ? $all_zad_elgadels->toArray() : []));

        return json_encode($places);
    }

    public function sync_places_data()
    {
        try {
            DB::beginTransaction();

            $data = [
                [
                    'key' => 'WHAT_WE_DO_TITLE',

                    'isEnv' => false,
                    'value' => 'ماذا نقدم في هودج',
                    'group' => 'what_we_do',
                    'type' => 'text',
                    'editable' => true,
                    'user_id' => 2

                ],
                [
                    'key' => 'WHAT_WE_DO_DESCRIPTION',

                    'isEnv' => false,
                    'value' => 'خدماتنا تتميز بأنها مـــا يتمنـــــــــاه المســــافر',
                    'group' => 'what_we_do',
                    'type' => 'text',
                    'editable' => true,
                    'user_id' => 2

                ],

                [
                    'key' => 'WHAT_WE_DO_CARD_ICON1',

                    'isEnv' => false,
                    'value' => ' 
                    <svg width="4rem" height="4rem" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 3C0 1.34315 1.34315 0 3 0H61C62.6569 0 64 1.34315 64 3V61C64 62.6569 62.6569 64 61 64H3C1.34315 64 0 62.6569 0 61V3Z" fill="#2C085D"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M32.0002 16.0008V46.77C32.0002 46.77 24.0921 43.5605 20.4422 35.8578C16.7923 28.155 17.2493 17.8846 17.2493 17.8846L32.0002 16.0008ZM32.0002 16.0008V46.77C32.0002 46.77 39.9083 43.5605 43.5582 35.8578C47.2081 28.155 46.7511 17.8846 46.7511 17.8846L32.0002 16.0008Z" fill="white"></path>
                        <path d="M25.8463 30.4858L30.3218 34.4621L38.154 27.0775" stroke="black" stroke-width="2.46154" stroke-linecap="round"></path>
                    </svg>',
                    'group' => 'what_we_do',
                    'type' => 'textarea',
                    'editable' => true,
                    'user_id' => 2

                ],
                [
                    'key' => 'WHAT_WE_DO_CARD_TITLE1',

                    'isEnv' => false,
                    'value' => 'آمن وموثوق',
                    'group' => 'what_we_do',
                    'type' => 'text',
                    'editable' => true,
                    'user_id' => 2

                ],
                [
                    'key' => 'WHAT_WE_DO_CARD_DETAILS1',

                    'isEnv' => false,
                    'value' => 'هذا نص وهمي قابل للتغير بنص بديل ومناسب لهذا الموضوع',
                    'group' => 'what_we_do',
                    'type' => 'text',
                    'editable' => true,
                    'user_id' => 2

                ],

                [
                    'key' => 'WHAT_WE_DO_CARD_ICON2',

                    'isEnv' => false,
                    'value' => '
                    <svg width="4rem" height="4rem" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="64" height="64" rx="3" fill="#2C085D"></rect>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12.8344 17.9018C12.5 17.3463 12.3077 16.6957 12.3077 16.0002C12.3077 13.961 13.9608 12.3079 16 12.3079C18.0392 12.3079 19.6923 13.961 19.6923 16.0002C19.6923 16.6957 19.5 17.3463 19.1656 17.9018L16 23.3848L12.8344 17.9018ZM15.9709 17.8463C14.9647 17.8308 14.1539 17.0103 14.1539 16.0004C14.1539 14.9808 14.9804 14.1542 16 14.1542C17.0196 14.1542 17.8462 14.9808 17.8462 16.0004C17.8462 17.0103 17.0354 17.8308 16.0292 17.8463H15.9709Z" fill="white"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M43.6036 44.9787C43.2692 44.4232 43.0769 43.7726 43.0769 43.0771C43.0769 41.0379 44.73 39.3848 46.7692 39.3848C48.8084 39.3848 50.4615 41.0379 50.4615 43.0771C50.4615 43.7726 50.2692 44.4233 49.9348 44.9787L46.7692 50.4617L43.6036 44.9787ZM46.7401 44.9232C45.734 44.9077 44.9231 44.0872 44.9231 43.0773C44.9231 42.0577 45.7497 41.2312 46.7693 41.2312C47.7889 41.2312 48.6154 42.0577 48.6154 43.0773C48.6154 44.0872 47.8046 44.9077 46.7984 44.9232H46.7401Z" fill="white"></path>
                        <path d="M31.6889 16.619C33.8702 16.5545 36.0283 16.6653 37.932 17.0432C38.2654 17.1094 38.5893 16.8928 38.6554 16.5594C38.7216 16.2261 38.505 15.9022 38.1716 15.836C36.1481 15.4343 33.8913 15.3225 31.6525 15.3888C31.3128 15.3988 31.0455 15.6824 31.0556 16.0221C31.0656 16.3618 31.3492 16.6291 31.6889 16.619Z" fill="white"></path>
                        <path d="M25.3942 17.2329C25.7298 17.1791 25.9582 16.8634 25.9044 16.5278C25.8506 16.1922 25.535 15.9638 25.1994 16.0176C24.0436 16.203 22.9671 16.414 22.0167 16.6313L22.2909 17.8311C23.2141 17.6201 24.2641 17.4141 25.3942 17.2329Z" fill="white"></path>
                        <path d="M44.146 18.8549C43.9269 18.5951 43.5387 18.5621 43.2789 18.7813C43.0191 19.0004 42.9861 19.3887 43.2053 19.6484C43.7928 20.3451 44.1825 21.1918 44.3124 22.2306C44.4189 23.0825 44.3143 23.8581 44.0398 24.5769C43.9185 24.8944 44.0776 25.2501 44.3952 25.3714C44.7127 25.4926 45.0683 25.3335 45.1896 25.016C45.536 24.1088 45.6652 23.13 45.5336 22.078C45.3745 20.8044 44.8882 19.7347 44.146 18.8549Z" fill="white"></path>
                        <path d="M41.4579 29.2661C41.7381 29.0737 41.8092 28.6907 41.6168 28.4105C41.4244 28.1303 41.0413 28.0592 40.7612 28.2516C39.4471 29.154 37.8589 29.9653 36.1324 30.7206C35.8211 30.8568 35.6791 31.2197 35.8153 31.531C35.9515 31.8424 36.3144 31.9844 36.6258 31.8482C38.3871 31.0776 40.0538 30.2305 41.4579 29.2661Z" fill="white"></path>
                        <path d="M31.5937 33.8318C31.9126 33.7144 32.0761 33.3607 31.9587 33.0417C31.8414 32.7228 31.4877 32.5593 31.1687 32.6767C30.7285 32.8386 30.2882 32.9993 29.8505 33.159L29.8482 33.1599C28.5607 33.6297 27.2953 34.0914 26.1175 34.5549C25.8013 34.6793 25.6458 35.0366 25.7702 35.3529C25.8947 35.6691 26.252 35.8246 26.5682 35.7002C27.7304 35.2428 28.9747 34.7888 30.2578 34.3206L30.2596 34.3199C30.7003 34.1591 31.1456 33.9966 31.5937 33.8318Z" fill="white"></path>
                        <path d="M21.8684 37.9746C22.1499 37.7843 22.2239 37.4017 22.0335 37.1202C21.8432 36.8386 21.4606 36.7646 21.1791 36.955C20.2567 37.5785 19.5051 38.2907 19.1299 39.1349C18.6208 40.2804 18.5226 41.398 18.8313 42.448C18.9272 42.7741 19.2692 42.9607 19.5953 42.8649C19.9213 42.769 20.108 42.427 20.0121 42.1009C19.7968 41.3684 19.8472 40.5515 20.2546 39.6347C20.4926 39.0994 21.0222 38.5466 21.8684 37.9746Z" fill="white"></path>
                        <path d="M23.8874 45.6325C23.5781 45.4917 23.2132 45.6282 23.0723 45.9375C22.9314 46.2468 23.0679 46.6117 23.3772 46.7526C24.9875 47.486 26.9556 48.0663 29.1896 48.4803C29.5237 48.5422 29.8449 48.3215 29.9068 47.9874C29.9687 47.6532 29.748 47.3321 29.4139 47.2701C27.249 46.8689 25.3826 46.3136 23.8874 45.6325Z" fill="white"></path>
                        <path d="M35.2199 47.9267C34.8805 47.9093 34.5913 48.1704 34.5739 48.5098C34.5565 48.8492 34.8176 49.1385 35.157 49.1558C36.1284 49.2056 37.1288 49.2309 38.1538 49.2309V48.0002C37.149 48.0002 36.1697 47.9753 35.2199 47.9267Z" fill="white"></path>
                    </svg>
                    ',
                    'group' => 'what_we_do',
                    'type' => 'textarea',
                    'editable' => true,
                    'user_id' => 2

                ],
                [
                    'key' => 'WHAT_WE_DO_CARD_TITLE2',

                    'isEnv' => false,
                    'value' => 'سهولة السفر',
                    'group' => 'what_we_do',
                    'type' => 'text',
                    'editable' => true,
                    'user_id' => 2

                ],
                [
                    'key' => 'WHAT_WE_DO_CARD_DETAILS2',

                    'isEnv' => false,
                    'value' => 'هذا نص وهمي قابل للتغير بنص بديل ومناسب لهذا الموضوع',
                    'group' => 'what_we_do',
                    'type' => 'text',
                    'editable' => true,
                    'user_id' => 2

                ],

                [
                    'key' => 'WHAT_WE_DO_CARD_ICON3',

                    'isEnv' => false,
                    'value' => '
                    <svg width="4rem" height="4rem" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="64" height="64" rx="3" fill="#2C085D"></rect>
                        <path d="M32.967 13.9452C32.433 13.4029 31.5671 13.4029 31.0331 13.9452L20.6593 24.4806C19.7978 25.3555 20.408 26.8515 21.6263 26.8515H26.53C26.53 29.9196 28.979 32.4068 32 32.4068C35.0211 32.4068 37.4701 29.9196 37.4701 26.8515H42.3738C43.5921 26.8515 44.2023 25.3555 43.3408 24.4806L32.967 13.9452Z" fill="white"></path>
                        <path d="M44.3077 46.3658C44.3077 51.2324 41.5998 50.7293 37.6389 49.9935C35.9496 49.6796 34.0323 49.3234 32 49.3234C29.9677 49.3234 28.0505 49.6796 26.3612 49.9935C22.4003 50.7293 19.6924 51.2324 19.6924 46.3658C19.6924 39.4235 25.2027 33.7956 32 33.7956C38.7974 33.7956 44.3077 39.4235 44.3077 46.3658Z" fill="white"></path>
                    </svg>
                    ',
                    'group' => 'what_we_do',
                    'type' => 'textarea',
                    'editable' => true,
                    'user_id' => 2

                ],
                [
                    'key' => 'WHAT_WE_DO_CARD_TITLE3',

                    'isEnv' => false,
                    'value' => 'مساعدة مباشرة',
                    'group' => 'what_we_do',
                    'type' => 'text',
                    'editable' => true,
                    'user_id' => 2

                ],
                [
                    'key' => 'WHAT_WE_DO_CARD_DETAILS3',

                    'isEnv' => false,
                    'value' => 'هذا نص وهمي قابل للتغير بنص بديل ومناسب لهذا الموضوع',
                    'group' => 'what_we_do',
                    'type' => 'text',
                    'editable' => true,
                    'user_id' => 2

                ]
            ];


            foreach ($data as $key => $value) {
                Setting::firstOrCreate($value);
            }

            DB::commit();
            return 'done';
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function save_places()
    {
        $data = Http::get('https://hawdaj7.com/api/v1/topics/12/page/1/count/10000');

        $topics = $data ?  json_decode($data, true)['topics'] : [];

        $data = collect($topics)->map(function ($item) {

            $galary = $this->save_gallaries($item);

            $city_id     = collect($item['fields'])->where('type', 6)->where('title', "المدينة")->first();
            $region_id   = collect($item['fields'])->where('type', 6)->where('title', "المناطق")->first();
            $seasons     = collect($item['fields'])->where('type', 7)->where('title', "افضل المواسم")->first();
            $price_id    = collect($item['fields'])->where('type', 6)->where('title', "الاسعار")->first();
            $temperature = collect($item['fields'])->where('type', 2)->where('title', "درجة الحرارة")->first();

            return [
                'id'          => $item['id'],
                'title'       => $item['title'],
                'description' => $item['details'],
                'views_num'   => $item['visits'],
                'city_id'     => $city_id ? $city_id['value'] : null,
                'region_id'   => $region_id ? $region_id['value'] : null,
                'seasons'     => $seasons ? $seasons['value'] : null,
                'price_id'    => $price_id ? $price_id['value'] : null,
                'temperature' => $temperature ? $temperature['value'] : null,
                'lat'         => $this->save_map($item)['lat'],
                'long'        => $this->save_map($item)['long'],
                'active'      => 1,
                'categories'  => collect($item['Joined_categories'])->map(function ($i) {
                    $cat =  Category::where('name', $i['title'])->first();
                    return $cat ? $cat->id : $i['id'];
                }),
                'image' => $this->save_image($item)
            ];
        });

        Place::insert($data->toArray());
    }

    public function save_prices()
    {
        $prices = [
            ['id' => 1, 'name' => 'مجاني', 'show' => 1],
            ['id' => 2, 'name' => 'مرتفع', 'show' => 1],
            ['id' => 3, 'name' => 'منخفض', 'show' => 1],
            ['id' => 4, 'name' => 'متوسط', 'show' => 1],
        ];

        Price::insert($prices);
    }

    public function save_map($item)
    {
        $map = Http::get('https://hawdaj7.com/api/v1/topic/maps/' . $item['id']);
        $map_data = $map ?  json_decode($map, true)['maps'] : [];
        return isset($map_data[0]) ? [
            'lat' => $map_data[0]['longitude'],
            'long' => $map_data[0]['latitude'],
        ] : [
            'lat' => null,
            'long' => null,
        ];
    }

    public function save_gallaries($item)
    {
        $galary = Http::get('https://hawdaj7.com/api/v1/topic/photos/' . $item['id']);
        $galary_data = $galary ?  json_decode($galary, true)['photos'] : [];
        $AllGalarydata = collect($galary_data)->map(function ($i) use ($item) {
            return [
                'parent_id' => $item['id'],
                'file' => $this->save_image($i),
                'type' => 'places',
            ];
        });

        Gallery::insert($AllGalarydata->toArray());
    }

    public function save_categories()
    {
        $data = Http::get('http://hawdaj7.com/api/v1/categories/12');

        $categories = $data ?  json_decode($data, true)['categories'] : [];
        foreach ($categories as $key => $value) {
            $cat = Category::create([
                'parent_id' => null,
                'icon' => $this->save_image($value),
                'name' => $value['title'],
            ]);

            if ($value['sub_categories']) {
                foreach ($value['sub_categories'] as $skey => $svalue) {
                    Category::create([
                        'parent_id' => $cat->id,
                        'icon' => $this->save_image($svalue),
                        'name' => $svalue['title'],
                    ]);
                }
            }
        }
    }

    public function save_citiest_and_regions()
    {
        $cities = [
            [
                'name' => 'عفيف',
                'region_id' => 1
            ],
            [
                'name' => 'القويعية',
                'region_id' => 1
            ],
            [
                'name' => 'الدوادمي',
                'region_id' => 1
            ],
            [
                'name' => 'الرين',
                'region_id' => 1
            ],
            [
                'name' => 'وادى الدواسر',
                'region_id' => 1
            ],
            [
                'name' => 'السليل',
                'region_id' => 1
            ],
            [
                'name' => 'الأفلاج',
                'region_id' => 1
            ],
            [
                'name' => 'الرين',
                'region_id' => 1
            ],
            [
                'name' => 'حوطة بنى تميم',
                'region_id' => 1
            ],
            [
                'name' => 'الحريق',
                'region_id' => 1
            ],
            [
                'name' => 'الخرج',
                'region_id' => 1
            ],
            [
                'name' => 'الدلم',
                'region_id' => 1
            ],
            [
                'name' => 'المزاحمية',
                'region_id' => 1
            ],
            [
                'name' => 'ضرما',
                'region_id' => 1
            ],
            [
                'name' => 'مرات',
                'region_id' => 1
            ],
            [
                'name' => 'شقراء',
                'region_id' => 1
            ],
            [
                'name' => 'الغاط',
                'region_id' => 1
            ],
            [
                'name' => 'الزلفى',
                'region_id' => 1
            ],
            [
                'name' => 'الخرج',
                'region_id' => 1
            ],
            [
                'name' => 'الرياض',
                'region_id' => 1
            ],
            [
                'name' => 'حريملاء',
                'region_id' => 1
            ],
            [
                'name' => 'ثادق',
                'region_id' => 1
            ],
            [
                'name' => 'رماح',
                'region_id' => 1
            ],
            [
                'name' => 'المجمعة',
                'region_id' => 1
            ],
            [
                'name' => 'العيص',
                'region_id' => 3
            ],
            [
                'name' => 'العلا',
                'region_id' => 3
            ],
            [
                'name' => 'خبير',
                'region_id' => 3
            ],
            [
                'name' => 'ينبع',
                'region_id' => 3
            ],
            [
                'name' => 'المدينة المنورة',
                'region_id' => 3
            ],
            [
                'name' => 'الحناكية',
                'region_id' => 3
            ],
            [
                'name' => 'بدر',
                'region_id' => 3
            ],
            [
                'name' => 'وادى الفرع',
                'region_id' => 3
            ],
            [
                'name' => 'رابغ',
                'region_id' => 2
            ],
            [
                'name' => 'خليص',
                'region_id' => 2
            ],
            [
                'name' => 'الكامل',
                'region_id' => 2
            ],
            [
                'name' => 'الجموم',
                'region_id' => 2
            ],
            [
                'name' => 'جدة',
                'region_id' => 2
            ],
            [
                'name' => 'الجموم',
                'region_id' => 2
            ],
            [
                'name' => 'مكة المكرمة',
                'region_id' => 2
            ],
            [
                'name' => 'الطائف',
                'region_id' => 2
            ],
            [
                'name' => 'بحرة',
                'region_id' => 2
            ],
            [
                'name' => 'الليث',
                'region_id' => 2
            ],
            [
                'name' => 'القنفذة',
                'region_id' => 2
            ],
            [
                'name' => 'العرضيات',
                'region_id' => 2
            ],
            [
                'name' => 'أضم',
                'region_id' => 2
            ],
            [
                'name' => 'ميسان',
                'region_id' => 2
            ],
            [
                'name' => 'تربة',
                'region_id' => 2
            ],
            [
                'name' => 'رنية',
                'region_id' => 2
            ],
            [
                'name' => 'الخرمة',
                'region_id' => 2
            ],
            [
                'name' => 'الموية',
                'region_id' => 2
            ],
            [
                'name' => 'بريدة',
                'region_id' => null
            ],
            [
                'name' => 'عنيزة',
                'region_id' => null
            ],
            [
                'name' => 'الرس',
                'region_id' => null
            ],
            [
                'name' => 'المذنب',
                'region_id' => null
            ],
            [
                'name' => 'البكيرية',
                'region_id' => null
            ],
            [
                'name' => 'البدائع',
                'region_id' => null
            ],
            [
                'name' => 'الأسياح',
                'region_id' => null
            ],
            [
                'name' => 'النبهانية',
                'region_id' => null
            ],
            [
                'name' => 'الشماسية',
                'region_id' => null
            ],
            [
                'name' => 'عيون الجواء',
                'region_id' => null
            ],
            [
                'name' => 'رياض الخبراء',
                'region_id' => null
            ],
            [
                'name' => 'عقلة الصقور',
                'region_id' => null
            ],
            [
                'name' => 'ضرية',
                'region_id' => null
            ],
            [
                'name' => 'أبها',
                'region_id' => null
            ],
            [
                'name' => 'خميس مشيط',
                'region_id' => null
            ],
            [
                'name' => 'بيشة',
                'region_id' => null
            ],
            [
                'name' => 'النماص',
                'region_id' => null
            ],
            [
                'name' => 'محايل عسير',
                'region_id' => null
            ],
            [
                'name' => 'ظهران الجنوب',
                'region_id' => null
            ],
            [
                'name' => 'تثليث',
                'region_id' => null
            ],
            [
                'name' => 'سراة عبيدة',
                'region_id' => null
            ],
            [
                'name' => 'رجال ألمع',
                'region_id' => null
            ],
            [
                'name' => 'بلقرن',
                'region_id' => null
            ],
            [
                'name' => 'أحد رفيدة',
                'region_id' => null
            ],
            [
                'name' => 'المجاردة',
                'region_id' => null
            ],
            [
                'name' => 'البرك',
                'region_id' => null
            ],
            [
                'name' => 'بارق',
                'region_id' => null
            ],
            [
                'name' => 'تنومة',
                'region_id' => null
            ],
            [
                'name' => 'طريب',
                'region_id' => null
            ],
            [
                'name' => 'تبوك',
                'region_id' => null
            ],
            [
                'name' => 'الوجه',
                'region_id' => null
            ],
            [
                'name' => 'ضبا',
                'region_id' => null
            ],
            [
                'name' => 'تيماء',
                'region_id' => null
            ],
            [
                'name' => 'أملج',
                'region_id' => null
            ],
            [
                'name' => 'حقل',
                'region_id' => null
            ],
            [
                'name' => 'البدع',
                'region_id' => null
            ],
            [
                'name' => 'حائل',
                'region_id' => null
            ],
            [
                'name' => 'بقعاء',
                'region_id' => null
            ],
            [
                'name' => 'الغزالة',
                'region_id' => null
            ],
            [
                'name' => 'الشنان',
                'region_id' => null
            ],
            [
                'name' => 'الحائط',
                'region_id' => null
            ],
            [
                'name' => 'السليمي',
                'region_id' => null
            ],
            [
                'name' => 'الشملي',
                'region_id' => null
            ],
            [
                'name' => 'موقق',
                'region_id' => null
            ],
            [
                'name' => 'سميراء',
                'region_id' => null
            ],
            [
                'name' => 'عرعر',
                'region_id' => null
            ],
            [
                'name' => 'رفحاء',
                'region_id' => null
            ],
            [
                'name' => 'طريف',
                'region_id' => null
            ],
            [
                'name' => 'العويقيلة',
                'region_id' => null
            ],
            [
                'name' => 'جازان',
                'region_id' => null
            ],
            [
                'name' => 'صبيا',
                'region_id' => null
            ],
            [
                'name' => 'أبو عريش',
                'region_id' => null
            ],
            [
                'name' => 'صامطة',
                'region_id' => null
            ],
            [
                'name' => 'بيش',
                'region_id' => null
            ],
            [
                'name' => 'الدرب',
                'region_id' => null
            ],
            [
                'name' => 'الحرث',
                'region_id' => null
            ],
            [
                'name' => 'ضمد',
                'region_id' => null
            ],
            [
                'name' => 'الريث',
                'region_id' => null
            ],
            [
                'name' => 'جزر فرسان',
                'region_id' => null
            ],
            [
                'name' => 'الدائر',
                'region_id' => null
            ],
            [
                'name' => 'العارضة',
                'region_id' => null
            ],
            [
                'name' => 'أحد المسارحة',
                'region_id' => null
            ],
            [
                'name' => 'العيدابي',
                'region_id' => null
            ],
            [
                'name' => 'فيفاء',
                'region_id' => null
            ],
            [
                'name' => 'الطوال',
                'region_id' => null
            ],
            [
                'name' => 'هروب',
                'region_id' => null
            ],
            [
                'name' => 'شرورة',
                'region_id' => null
            ],
            [
                'name' => 'حبونا',
                'region_id' => null
            ],
            [
                'name' => 'بدر الجنوب',
                'region_id' => null
            ],
            [
                'name' => 'يدمه',
                'region_id' => null
            ],
            [
                'name' => 'ثار',
                'region_id' => null
            ],
            [
                'name' => 'خباش',
                'region_id' => null
            ],
            [
                'name' => 'الخرخير',
                'region_id' => null
            ],
            [
                'name' => 'الباحة',
                'region_id' => null
            ],
            [
                'name' => 'بلجرشي',
                'region_id' => null
            ],
            [
                'name' => 'المندق',
                'region_id' => null
            ],
            [
                'name' => 'المخواة',
                'region_id' => null
            ],
            [
                'name' => 'قلوة',
                'region_id' => null
            ],
            [
                'name' => 'العقيق',
                'region_id' => null
            ],
            [
                'name' => 'القرى',
                'region_id' => null
            ],
            [
                'name' => 'غامد الزناد',
                'region_id' => null
            ],
            [
                'name' => 'الحجرة',
                'region_id' => null
            ],
            [
                'name' => 'بني حسن',
                'region_id' => null
            ],
            [
                'name' => 'سكاكا',
                'region_id' => null
            ],
            [
                'name' => 'القريات',
                'region_id' => null
            ],
            [
                'name' => 'دومة الجندل',
                'region_id' => null
            ],
            [
                'name' => 'طبرجل',
                'region_id' => null
            ],
            [
                'name' => 'الدمام',
                'region_id' => null
            ],
            [
                'name' => 'الإحساء',
                'region_id' => null
            ],
            [
                'name' => 'حفر الباطن',
                'region_id' => null
            ],
            [
                'name' => 'الجييل',
                'region_id' => null
            ],
            [
                'name' => 'القطيف',
                'region_id' => null
            ],
            [
                'name' => 'الخبر',
                'region_id' => null
            ],
            [
                'name' => 'الخفجي',
                'region_id' => null
            ],
            [
                'name' => 'رأس تنورة',
                'region_id' => null
            ],
            [
                'name' => 'بقيق',
                'region_id' => null
            ],
            [
                'name' => 'النعيرية',
                'region_id' => null
            ],
            [
                'name' => 'قرية العليا',
                'region_id' => null
            ],
            [
                'name' => 'العديد',
                'region_id' => null
            ],
        ];

        foreach ($cities as $key => $value) {
            City::create($value);
        }
    }

    function save_image($item)
    {
        if (isset($item['photo_file'])) {
            $image_url = $item['photo_file'];
            $folder = 'topics';
        } elseif (isset($item['url'])) {
            $image_url = $item['url'];
            $folder = 'topics';
        } elseif (isset($item['photo'])) {
            $image_url = $item['photo'];
            $folder = 'category';
        }

        if (isset($image_url)) {
            $image = explode('/', $image_url);
            // $contents = file_get_contents($image_url);
            // Storage::disk('public')->put($folder . '/' . end($image), $contents);
            return $folder . '/' . end($image);
        }
        return null;
    }

    public function get_all_swalefs()
    {
        $places =[];
        return view('front.swalef.all' , compact('places'));
    }
}
