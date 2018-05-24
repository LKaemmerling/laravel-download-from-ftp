<?php

namespace App\Http\Controllers;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 *
 */
class FTPController extends Controller
{

    /**
     * Cache Time in Minutes
     * @var int
     */
    protected static $cache_time = 360;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list_folders()
    {
        return view('list_folders');
    }

    function multi_explode(array $delimiter, $string)
    {
        $d = array_shift($delimiter);
        if ($d != null) {
            $tmp = explode($d, $string);
            foreach ($tmp as $key => $o) {
                $out[$key] = $this->multi_explode($delimiter, $o);
            }
        } else {
            return $string;
        }

        return $out;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_list_folders()
    {
        $array = [];
        // You should cache the response from the ftp, since the connection to the ftp server could need some time.
        if (Cache::has('files')) {
            $array = Cache::get('files');
        } else {
            $files = Storage::disk('ftp')->allFiles();
            Cache::put('__files', $files, self::$cache_time);
            $resp = collect($files)->map(function ($r, $index) use (&$array) {
                return self::set_array_per_slash_notation($array, ltrim($r), $index);
            });
            Cache::put('files', $array, self::$cache_time);
        }

        return response()->json($array);
    }

    /**
     * Set's an array per slash notation
     * @Copy of the internal Laravel Arr:set Method
     *
     * @param $array
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public static function set_array_per_slash_notation(&$array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }

        $keys = explode('/', $key);

        if (strlen($keys[0]) == 0) {
            array_shift($keys);
        }
        while (count($keys) > 1) {
            $key = array_shift($keys);

            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }

    /**
     * @param $file
     *
     * @return mixed
     */
    public function download_file($file)
    {
        $files = Cache::get('__files');

        return Storage::disk('ftp')->download($files[$file]);
    }
}