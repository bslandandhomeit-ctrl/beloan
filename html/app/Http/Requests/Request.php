<?php
namespace App\Http\Requests;

use Illuminate\Support\Facades\Request as FRequest;

class Request extends FRequest {

    /**
     * Get all of the input and files for the request.
     *
     * @param bool $trim
     * @param bool $clean
     *
     * @return string[]
     */
    public static function all($trim = true, $clean = true)
    {
        $values = parent::all();
        return self::clean($values, $trim, $clean);
    }
    /**
     * Get an input item from the request.
     *
     * @param string $key
     * @param string $default
     * @param bool   $trim
     * @param bool   $clean
     *
     * @return string
     */
    public static function get($key, $default = null, $trim = true, $clean = true)
    {
        $value = parent::input($key, $default);
        return self::clean($value, $trim, $clean);
    }
    /**
     * Get an input item from the request.
     *
     * This is an alias to the get method.
     *
     * @param string $key
     * @param string $default
     * @param bool   $trim
     * @param bool   $clean
     *
     * @return string
     */
    public static function input($key=null, $default = null, $trim = true, $clean = true)
    {
        return self::get($key, $default, $trim, $clean);
    }
    /**
     * Get a subset of the items from the input data.
     *
     * @param string|string[] $keys
     * @param bool            $trim
     * @param bool            $clean
     *
     * @return string[]
     */
    public static function only($keys, $trim = true, $clean = true)
    {
        $values = [];
        foreach ((array) $keys as $key) {
            $values[$key] = self::get($key, null, $trim, $clean);
        }
        return $values;
    }
    /**
     * Get all of the input except for a specified array of items.
     *
     * @param string|string[] $keys
     * @param bool            $trim
     * @param bool            $clean
     *
     * @return string[]
     */
    public static function except($keys, $trim = true, $clean = true)
    {
        $values = parent::except($keys);
        return self::clean($values, $trim, $clean);
    }
    /**
     * Get a mapped subset of the items from the input data.
     *
     * @param string[] $keys
     * @param bool     $trim
     * @param bool     $clean
     *
     * @return string[]
     */
    public static function map(array $keys, $trim = true, $clean = true)
    {
        $values = self::only(array_keys($keys), $trim, $clean);
        $new = [];
        foreach ($keys as $key => $value) {
            $new[$value] = array_get($values, $key);
        }
        return $new;
    }
    /**
     * Get an old input item from the request.
     *
     * @param string $key
     * @param string $default
     * @param bool   $trim
     * @param bool   $clean
     *
     * @return string
     */
    public static function old($key, $default = null, $trim = true, $clean = true)
    {
        $value = parent::old($key, $default);
        return self::clean($value, $trim, $clean);
    }

    /**
     * Clean a specified value or values.
     *
     * @param string|string[] $value
     * @param bool            $trim
     * @param bool            $clean
     *
     * @return string|string[]
     */
    protected  static function clean($value, $trim = true, $clean = true)
    {
        $final = null;
        if ($value !== null) {
            if (is_array($value)) {
                $all = $value;
                $final = [];
                foreach ($all as $key => $value) {
                    if ($value !== null) {
                        $final[$key] = self::clean($value, $trim, $clean);
                    }
                }
            } else {
                if ($value !== null) {
                     $final = self::process((string) $value, $trim, $clean);
                }
            }
        }
        return $final;
    }
      /**
     * Process a specified value.
     *
     * @param string $value
     * @param bool   $trim
     * @param bool   $clean
     *
     * @return string
     */
    protected static function process($value, $trim = true, $clean = true)
    {
        if ($trim) {
            $value = trim($value);
        }
        if ($clean) {
            $value = Security::xss_clean($value);
        }
        return $value;
    }
}