<?php
namespace App\Src;

class ApiResponse
{
    public static function data($data = [], $code = 200)
    {
        return response($data, $code);
    }

    public static function success()
    {
        return response([
            'message'=> 'success'
        ], 200);
    }

    public static function error($error = 'error', $code = 422)
    {
        return response([
            'message'=> $error
        ], $code);
    }
}
