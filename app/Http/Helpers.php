<?php

class Helpers
{
    /*
     * Method to strip tags globally.
    */
    public static function globalXssClean()
    {
        // Recursive cleaning for array [] inputs, not just strings.
        $sanitized = static::arrayStripTags(Request::all());
        Request::merge($sanitized);
    }

    /**
     * Method to strip tags
     *
     * @param $array
     * @return array
     */
    public static function arrayStripTags($array)
    {
        $result = array();

        foreach ($array as $key => $value) {
            // Don't allow tags on key either, maybe useful for dynamic forms.
            $key = strip_tags($key);

            // If the value is an array, we will just recurse back into the
            // function to keep stripping the tags out of the array,
            // otherwise we will set the stripped value.
            if (is_array($value)) {
                $result[$key] = static::arrayStripTags($value);
            } else {
                // I am using strip_tags(), you may use htmlentities(),
                // also I am doing trim() here, you may remove it, if you wish.
                $result[$key] = trim(strip_tags($value));
            }
        }

        return $result;
    }

    /**
     * Escape output
     *
     * @param $value
     * @return string
     */
    public static function sanitizeOutput($value)
    {
        return addslashes($value);
    }

    /*
     * Convert date
     */
    public static function convertDate($convertDate)
    {
        return date('d-m-Y', strtotime($convertDate));
    }

    /**
     * Send success ajax response
     *
     * @param string $message
     * @param array $result
     * @return array
     */
    public static function sendSuccessAjaxResponse($message = '', $result = [])
    {
        $response = [
            'status'    => true,
            'message'   => $message,
            'data'      => $result
        ];

        return $response;
    }

    /**
     * Send failure ajax response
     *
     * @param string $message
     * @return array
     */
    public static function sendFailureAjaxResponse($message = '')
    {
        $message = $message == '' ? config('app.message.default_error') : $message;

        $response = [
            'status'    => false,
            'message'   => $message
        ];

        return $response;
    }
}
