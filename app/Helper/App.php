<?php

use App\Models\Place;
use App\Models\User;
use App\Models\VisitSite;
use BaconQrCode\Encoder\QrCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Nwidart\Modules\Facades\Module;

if (!function_exists('distance')) {
    function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        //   echo distance(32.9697, -96.80322, 29.46786, -98.53506, "M") . " Miles<br>";
        //   echo distance(32.9697, -96.80322, 29.46786, -98.53506, "K") . " Kilometers<br>";
        //   echo distance(32.9697, -96.80322, 29.46786, -98.53506, "N") . " Nautical Miles<br>";
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
    }
}
if (!function_exists('v_image')) {
    function v_image($ext = null)
    {
        return ($ext === null) ? 'mimes:jpg,png,jpeg,png,gif,bmp' : 'mimes:' . $ext;
    }
}
if (!function_exists('visit')) {
    function visit($data)
    {
        $ins = VisitSite::updateOrCreate(
            [
                'ip' => $data['ip'],
                'page' => $data['page'],
            ],
            $data
        );
        if ($ins) {
            return true;
        } else {
            return false;
        }
    }
}
if (!function_exists('genrateEmailLink')) {
    function genrateEmailLink($email = null)
    {
        return '<p><a href="mailto:' . $email . '">' . $email . '</a>';
    }
}

if (!function_exists('genrateQrCode')) {
    function genrateQrCode($request_id = null)
    {
        $base = '';
        if ($request_id) {
            $image = $request_id . time() . '.svg';

            $qr_image = QrCode::size(50)
                ->format('svg')
                ->generate($request_id, $image);
            $base = svgToBase64($image);
            unlink($image);
        }
        return '<img src="' . $base . '"/>';
    }
}

if (!function_exists('genrateCancelButton')) {
    function genrateCancelButton($material_request_id = null)
    {
        $link = '';
        if ($material_request_id) {
            $link = '<a class="btn btn-sm btn-danger" target="_blank" href="' . route('dashboard.meeting-cancel', $material_request_id) . '">';
            $link .= __('dashboard.cancel_meeting');
            $link .= '</a>';
        }
        return $link;
    }
}

if (!function_exists('genrateConformBtn')) {
    function genrateConformBtn($link = null)
    {
        return '<a href="' . $link . '" target="_blank" class="btn btn-sm btn-primary">' . __('dashboard.tabConf') . '</a>';
    }
}

if (!function_exists('dateFormat')) {
    function dateFormat($date): string
    {
        return !is_numeric($date)
            ? Jenssegers\Date\Date::parse($date)->format('j F Y')
            : '----';
    }
}

if (!function_exists('timeFormat')) {
    function timeFormat($time): string
    {
        return Jenssegers\Date\Date::parse($time)->format('H:i A');
    }
}

if (!function_exists('resolveLang')) {
    function resolveLang($name)
    {
        if (is_array($name)) {
            $result = $name[app()->getLocale()];
            if (!$result) {
                $result = $name[(app()->getLocale() === 'en') ? 'ar' : 'en'];
            }

            return $result;
        }

        return $name;
    }
}

if (!function_exists('getFileDir')) {
    function getFileDir()
    {
        return (app()->getLocale() === 'en') ? '' : 'rtl.';
    }
}

if (!function_exists('userRoot')) {
    function userRoot()
    {
        return User::find(1);
    }
}

if (!function_exists('isRoot')) {
    function isRoot()
    {
        return auth()->id() == 1 || auth()->user()->hasRole('root');
    }
}

if (!function_exists('activeMenu')) {

    function activeMenu($index, ...$name): string
    {

        foreach ($name as $item) {
            if (request()->segment($index) == $item) {
                return 'menu-item-active';
            }
        }

        return "";
    }
}

if (!function_exists('errorMessage')) {
    function errorMessage($message = null)
    {
        $message = trans('dashboard.something_error') . '' . (env('APP_DEBUG') ? " : $message" : '');

        return request()->expectsJson()
            ? response()->json(['message' => $message], 400)
            : redirect()->back()->with(['status' => 'error', 'message' => $message]);
    }
}

if (!function_exists('successMessage')) {
    function successMessage($message = 'success')
    {
        return request()->expectsJson()
            ? response()->json(['message' => $message])
            : redirect()->back()->with(['status' => 'success', 'message' => $message]);
    }
}

if (!function_exists('utf8_strrev')) {
    function utf8_strrev($str = null)
    {
        if ($str) {
            preg_match_all('/./us', $str, $ar);
            return join('', array_reverse($ar[0]));
        }
        return null;
    }
}

function svgToBase64($filepath)
{
    if (file_exists($filepath)) {

        $filetype = pathinfo($filepath, PATHINFO_EXTENSION);

        if ($filetype === 'svg') {
            $filetype .= '+xml';
        }

        $get_img = file_get_contents($filepath);
        return 'data:image/' . $filetype . ';base64,' . base64_encode($get_img);
    }
}

if (!function_exists('resolveDateTime')) {
    function resolveDateTime($date, $time)
    {
        try {
            if (is_null($date)) {
                $date = carbon()->now()->toDateString();
            }

            $date = new DateTime($date . ' ' . $time);
        } catch (Exception $ex) {
            $time = date('H:i', mktime(0, 0, $time));
            $date = new DateTime($date . ' ' . $time);
        }

        $new_time = $date->format('Y-m-d H:i');

        return Carbon::parse($new_time);
    }
}

if (!function_exists('diffInMinutesHelper')) {
    function diffInMinutesHelper($start_time, $end_time)
    {
        $interval = $start_time->diff($end_time);
        $hours = $interval->format('%h');
        $minutes = $interval->format('%i');
        return $hours * 60 + $minutes;
    }
}

if (!function_exists('dateFormat')) {
    function dateFormat($date): string
    {
        return !is_numeric($date)
            ? Jenssegers\Date\Date::parse($date)->format('j F Y')
            : '----';
    }
}

if (!function_exists('timeFormat')) {
    function timeFormat($time): string
    {
        return Jenssegers\Date\Date::parse($time)->format('h:i a');
    }
}

if (!function_exists('resolveLang')) {
    function resolveLang($name)
    {
        if (is_array($name)) {
            $result = $name[getLang()];
            if (!$result) {
                $result = $name[(getLang() === 'en') ? 'ar' : 'en'];
            }

            return $result;
        }
        return $name;
    }
}

if (!function_exists('getFileDir')) {
    function getFileDir()
    {
        return (getLang() === 'en') ? '' : 'rtl.';
    }
}

if (!function_exists('unKnownError')) {
    function unKnownError($message = null)
    {
        $message = trans('dashboard.something_error') . '' . (env('APP_DEBUG') ? " : $message" : '');

        return request()->expectsJson()
            ? response()->json(['message' => $message], 400)
            : redirect()->back()->with(['status' => 'error', 'message' => $message]);
    }
}

if (!function_exists('resolvePhoto')) {
    function resolvePhoto($image = null, $type = 'none')
    {
        $result = ($type === 'user'
            ? asset('dashboard_assets/media/users/default.jpg')
            : asset('dashboard_assets/media/blank.png'));

        if (is_null($image)) {
            return $result;
        }

        if (Str::startsWith($image, 'http')) {
            return $image;
        }

        return Storage::disk('public')->exists($image)
            ? Storage::disk('public')->url($image)
            : $result;
    }
}

if (!function_exists('userRoot')) {
    function userRoot()
    {
        return User::find(1);
    }
}

if (!function_exists('getLang')) {
    function getLang()
    {
        return app()->getLocale();
    }
}

if (!function_exists('primaryID')) {
    function primaryID($id = null)
    {
        $user = $id ? User::find($id) : auth()->user();
        if (!empty($user)) {
            return $user->parent_id ?? $user->id;
        }
    }
}

if (!function_exists('Primary')) {
    function Primary()
    {
        return auth()->user()->parent_id ? User::find(auth()->user()->parent_id) : auth()->user();
    }
}

if (!function_exists('isRoot')) {
    function isRoot(): bool
    {
        return auth()->id() == 1 && is_null(auth()->user()->parent_id);
    }
}

if (!function_exists('getPermissions')) {

    function getPermissions($user)
    {
        return $user->roles->map(function ($role) {
            return $role->permissions;
        })->collapse();
    }
}

if (!function_exists('active')) {

    function active(...$items): string
    {
        $className = '';

        foreach ($items as $item) {
            if (request()->is("*/$item") || request()->is("*/$item/*")) {
                $className = 'menu-item-active';
                break;
            }
        }
        return $className;
    }
}

if (!function_exists('inputError')) {

    function inputError($name)
    {
        if (session('errors')) {

            return session('errors')->has($name) ? 'is-invalid' : '';
        }
    }
}

if (!function_exists('msgError')) {

    function msgError($name)
    {
        if (session('errors')) {

            return session('errors')->has($name) ? session('errors')->first($name) : '';
        }
    }
}

if (!function_exists('carbon')) {

    function carbon(): \Illuminate\Support\Carbon
    {
        return new \Illuminate\Support\Carbon;
    }
}

if (!function_exists('is_base64')) {
    function is_base64($s): bool
    {
        return (bool) preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s);
    }
}

if (!function_exists('shortModelName')) {
    function shortModelName($key)
    {
        $arr = [
            'SpeedModel' => 'forkliftspeed',
            'PpesModel' => 'safety',
            'BubblesModel' => 'bubbles',
        ];

        return $arr[$key] ?? $key;
    }
}

if (!function_exists('updateDotEnv')) {
    function updateDotEnv($key, $newValue, $delim = '')
    {

        $path = base_path('.env');
        // get old value from current env
        $oldValue = env($key);

        // was there any change?
        if ($oldValue === $newValue) {
            return;
        }

        // rewrite file content with changed data
        if (file_exists($path)) {
            // replace current value with new value
            file_put_contents(
                $path,
                str_replace(
                    $key . '=' . $delim . $oldValue . $delim,
                    $key . '=' . $delim . $newValue . $delim,
                    file_get_contents($path)
                )
            );
        }
    }
}

if (!function_exists('isModuleEnabled')) {
    function isModuleEnabled($moduleName)
    {

        $enabledModules = Module::allEnabled();
        foreach ($enabledModules as $module) {
            if (strtolower($module) === strtolower($moduleName)) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('writeTranslation')) {
    function writeTranslation($line, $lang = 'en')
    {
        $content = file(lang_path("$lang/dashboard.php"));
        $lines = array();

        $string = file_get_contents(lang_path("$lang/dashboard.php"));

        if (strpos($string, $line) !== false) {
            return true;
        }

        foreach ($content as $row) {
            $lines[] = $row;
            if (strpos($row, "return [") !== false || strpos($row, "return array(") !== false) {
                $lines[] = $line;
            }
        }

        file_put_contents(lang_path("$lang/dashboard.php"), $lines);

        return true;
    }
}

if (!function_exists('handleTrans')) {
    function handleTrans($trans, $return = null)
    {
        $key = Str::snake($trans);

        if ($return == null) {
            $return = $trans;
        }
        return Str::startsWith(__("dashboard.$key"), 'dashboard.') ? $return : __("dashboard.$key");
    }
}

if (!function_exists('sync_places')) {

    function sync_places()
    {
        $data = Http::get('https://hawdaj7.com/api/v1/topics/12/page/1/count/10');

        $topics = $data ?  json_decode($data, true)['topics'] : [];

        $data = collect($topics)->map(function ($item) {
            $image = explode('/', $item['photo_file']);
            return [
                'title'       => $item['title'],
                'description' => $item['details'],
                'views_num'   => $item['visits'],
                'city_id'     => $item['fields'][1]['value'],
                'region_id'   => $item['fields'][0]['value'],
                'seasons'     => $item['fields'][2]['value'],
                'price_id'    => $item['fields'][4]['value'],
                'categories'  => collect($item['Joined_categories'])->map(function ($i) {
                    return $i['id'];
                }),
                'image' => $image ? end($image) : null
            ];
        });

        return Place::insert($data->toArray());
    }
}
